<?php  
namespace Concrete\Package\Proevents\Src\ProEvents;

use Concrete\Core\Search\ItemList\Database\AttributedItemList as DatabaseItemList;
use \Concrete\Core\Legacy\Model;
use \Concrete\Core\Page\PageList;
use Concrete\Package\Proevents\Src\ProEvents\Event;
use Concrete\Package\Proevents\Src\ProEvents\EventPagination;
use Pagerfanta\Adapter\DoctrineDbalAdapter;
use Database;
use Loader;

/**
 *
 * An object that allows a filtered list of events to be returned.
 * @package ProEvents
 *
 **/
class EventList extends PageList
{
    /**
     * extending the filter to exclude excluded dates within the query
     */

    var $num = 0;
    var $template = '';
    var $calNum = null;

    public function __construct(StickyRequest $req = null)
    {
        $this->query = Database::get()->createQueryBuilder();
        $this->searchRequest = $req;
        $this->createQuery();

        $this->query->leftJoin('p', 'btProEventDates', 'event', 'p.cID = event.eventID');
        $this->query->addSelect("event.eID as eID");
        $this->query->addSelect("event.sttime as eventstart");
        $this->query->addSelect("event.entime as eventend");
        $this->query->addSelect("event.grouped as grouped");
    }


    /**
     * Filters by category
     * @param categories array
     * it's not possible to easily use the optimized data structure for select attributes specifically because of the
     * way they are single-string'd in the index table by value and not ID.  Thus, we are forced to use %LIKE%. no choice.
     */
    public function filterByCategories($categories)
    {
        if ($categories != null && !in_array('All Categories', $categories) && !in_array('', $categories)) {
            $ccount = count($categories);
            $cct = 0;
            $category = 'ak_event_category LIKE ';
            foreach ($categories as $category_item) {
                $category_item = str_replace("'", "\'", trim($category_item));
                //$category_item = str_replace('&','&amp;',$category_item);
                $category .= "'%$category_item%'";
                $cct++;
                if ($cct < $ccount) {
                    $category .= ' OR ak_event_category LIKE  ';
                }
            }
            $this->filter(false, $category);
        }
    }

    /**
     * Filters by section
     * @param section array
     */
    public function filterByDateRange($date1 = null, $date2 = null)
    {
        $this->setBaseQuery(
            ",event.eID as eID, min(event.date) AS eventdate, event.sttime AS eventstart, event.entime AS eventend"
        );
        $this->setupAttributeFilters(
            "left join btProEventDates event on p1.cID = event.eventID " . $this->exclude_filter
        );
        //$this->filter(false, "excluddates.eeID IS NULL");
        if($date2){
        	$this->filter(false, "event.date >= '$date1' AND event.date <= '$date2'");
        }else{
	        $this->filter(false, "event.date >= '$date1'");
        }
    }


    /**
     * Filters by date and limit num
     * @param date (string)
     */
    public function filterByAllDates($date = null, $time = null, $grouped = 1)
    {
        if (!$date) {
            $date = date("Y-m-d");
        }
        if (!$time) {
            $time = date("H:i:s");
        }
        $this->query->addSelect("event.date as eventdate");

        $this->query->andWhere(
            //"((DATE_FORMAT(event.date,'%Y-%m-%d') >= '$date') OR (DATE_FORMAT(CONCAT_WS(' ', event.date, event.entime),'%Y-%m-%d %H:%i:%s') >= '$date $time'))"
            "((event.date = '$date' AND event.sttime > '$time') OR event.date > '$date')"
        );

        if ($grouped) {
            $this->query->groupBy("event.grouped, event.eventID");
        }
    }


    /**
     * Filters by section
     * @param section array
     */
    public function filterArchiveDates($date = null)
    {
        if ($date == null) {
            $date = date("Y-m-d");
        }

        $this->query->addSelect("min(event.date) as eventdate");

        $this->query->andWhere("event.date < CURDATE()");

        $this->query->groupBy("event.grouped");
    }


    /**
     * Filters by date span
     * @param filters date by provided date span
     */
    public function filterBySpan($date, $date2)
    {
        $this->query->addSelect("event.date as eventdate");

        $this->query->andWhere(
            "(DATE_FORMAT(event.date,'%Y-%m-%d') >= DATE_FORMAT('$date','%Y-%m-%d') AND DATE_FORMAT(event.date,'%Y-%m-%d') <= DATE_FORMAT('$date2','%Y-%m-%d'))"
        );
    }


    /**
     * Filters by month
     * @param filters date by provided date month
     */
    public function filterByMonth($date = null)
    {
        if ($date == null) {
            $date = date("Y-m-d");
        }

        $this->query->addSelect("event.date as eventdate");

        $this->query->andWhere("(DATE_FORMAT(event.date,'%Y-%m') = DATE_FORMAT('$date','%Y-%m'))");
    }


    /**
     * Filters by FOLLOWING month
     * @param filters date by provided date month
     */
    public function filterByFollowingMonth($date = null)
    {
        if ($date == null) {
            $j = date('Y');
            $m = date('m');
            $date = date("Y-m-d", mktime(0, 0, 0, $m + 1, 1, $j));
        }

        $this->query->addSelect("event.date as eventdate");

        $this->query->andWhere("DATE_FORMAT(event.date,'%Y-%m') = DATE_FORMAT('$date','%Y-%m')");
    }


    /**
     * Filters by week
     * @param filters date by provided date week
     */
    public function filterByWeek()
    {
        $date = date('Y-m-d');
        $sunday = date('Y-m-d', strtotime('last Sunday', strtotime($date)));
        $to_saturday = date('Y-m-d', strtotime('+6 Days', strtotime($sunday)));

        $this->query->addSelect("min(event.date) as eventdate");

        $this->query->andWhere(
            "DATE_FORMAT(event.date,'%Y-%m-%d') >= DATE_FORMAT('$sunday','%Y-%m-%d') AND DATE_FORMAT(event.date,'%Y-%m-%d') <= DATE_FORMAT('$to_saturday','%Y-%m-%d')"
        );

        $this->query->groupBy("event.grouped");
    }


    /**
     * Filters by FOLLOWING week
     * @param filters date by provided date week
     */
    public function filterByFollowingWeek()
    {
        $date = date('Y-m-d');
        $sunday = date('Y-m-d', strtotime('next Sunday', strtotime($date)));
        $to_saturday = date('Y-m-d', strtotime('+6 Days', strtotime($sunday)));

        $this->query->addSelect("min(event.date) as eventdate");

        $this->query->andWhere(
            "DATE_FORMAT(event.date,'%Y-%m-%d') >= DATE_FORMAT('$sunday','%Y-%m-%d') AND DATE_FORMAT(event.date,'%Y-%m-%d') <= DATE_FORMAT('$to_saturday','%Y-%m-%d')"
        );

        $this->query->groupBy("event.grouped");
    }

    /**
     * Filters by day
     * @param filters date by provided date day
     */
    public function filterByDay($date = null)
    {
        if ($date == null) {
            $date = date("Y-m-d");
        }

        $this->query->addSelect("min(event.date) as eventdate");

        $this->query->andWhere("event.date = '$date'");

        $this->query->groupBy("event.grouped");
    }


    /**
     * Filters by eID
     * @param filters date by provided date eID
     */
    public function filterBySpecific($eID)
    {
        $this->query->addSelect("min(event.date) as eventdate");

        $this->query->andWhere("event.eID = '$eID'");
    }


    /**
     * Filters by Viewing User
     * @param filters dates by event_price
     */
    public function filterByUser($uID)
    {
        $this->query->leftJoin('p', 'btProEventUserSaved', 'saved', 'p.cID = saved.eventID');
        $this->query->andWhere("saved.uID = '$uID'");
    }

    /**
     * method contribution @mattdavey
     * Filters by User ID
     * @param filters uID by $userID
     */
    public function filterByUserId($userID)
    {
        $this->query->leftJoin('p1', 'Pages', 'pages', 'p1.cID = pages.cID');
        $this->query->andWhere("pages.uID = '$userID'");
    }


    /**
     * Filters by bookable
     * @param filters dates by bookable
     */
    public function filterByBookable()
    {
        $this->query->andWhere("event.status IS NOT NULL");
    }


    /**
     * Filters by status
     * @param filters dates by status
     */
    public function filterByStatus($status = 'available')
    {
        $this->query->andWhere("event.status = '$status'");
    }

    /**
     * Filters by event_qty
     * @param filters dates by event_qty
     */
    public function filterByQty($qty = 0)
    {
        $this->query->andWhere("event.event_qty = '$qty'");
    }

    /**
     * Filters by event_price
     * @param filters dates by event_price
     */
    public function filterByPrice($price = 0)
    {
        $this->query->andWhere("event.event_price = '$price'");
    }

    /**
     * set the current template
     */
    public function setEventTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * set the num of returns
     */
    public function setEventNum($num)
    {
        $this->num = $num;
    }

    /**
     * set the ordering
     */
    public function setEventOrdering($ordering)
    {
        switch ($ordering) {
            case 'DESC':
                $this->query->orderBy("event.date,event.sttime", "desc");
                break;
            default:
                $this->query->orderBy("event.date,event.sttime");
                break;
        }
    }

    /**
     * Filters dates by block custom view
     * detects block view name and chooses most efficient event list
     * @param if no theme is present, filters all days like a list limited by num
     */
    public function filterDates($date = null, $date2 = null)
    {

        $template = $this->template;
        $num = $this->num;

        if (substr_count($template, 'specific') > 0) {
            $this->filterBySpecific($date);
            $this->setItemsPerPage(1);
        } elseif (substr_count($template, 'archive') > 0) {
            $this->filterArchiveDates();
            if ($num > 0) {
                $this->setItemsPerPage($num);
            }
        } elseif (substr_count($template, 'following') > 0 && substr_count($template, 'month') > 0) {
            $this->filterByFollowingMonth($date);
            $this->calNum = 1;
        } elseif (substr_count($template, 'following') > 0 && substr_count($template, 'week') > 0) {
            $this->filterByFollowingWeek($date);
            $this->calNum = 1;
        } elseif (substr_count($template, 'day') > 0 || substr_count($template, 'today') > 0) {
            $this->filterByDay($date);
            if ($num > 0) {
                $this->setItemsPerPage($num);
            } else {
                $this->calNum = 1;
            }
        } elseif (substr_count($template, 'jquery') > 0 || substr_count($template, 'dynamic') > 0) {
            $this->filterBySpan($date, $date2);
            $this->calNum = 1;
        } elseif (substr_count($template, 'month') > 0 || substr_count($template, 'ajax_') > 0 || substr_count(
                $template,
                'full'
            ) > 0 || substr_count($template, 'responsive') > 0 || substr_count($template, 'calendar') > 0
        ) {
            $this->filterByMonth($date);
            $this->calNum = 1;
        } elseif (substr_count($template, 'week') > 0) {
            $this->filterByWeek($date);
            if ($num > 0) {
                $this->setItemsPerPage($num);
            } else {
                $this->calNum = 1;
            }
        } else {
            $this->filterByAllDates();
            if ($num > 0) {
                $this->setItemsPerPage($num);
            }
        }

    }

    /**
     * Filters dates by block custom view
     * detects block view name and chooses most efficient event list
     * @param if no theme is present, filters all days like a list limited by num
     */
    public function filterDatesByType($date = null, $date2 = null, $type = null, $num = null)
    {
        $list = 'filterBy' . $type;
        $this->$list($date, $date2);
    }


    /**
     * @return \Concrete\Core\Search\Pagination\Pagination|\Concrete\Core\Search\Pagination\PermissionablePagination
     */
    public function getEventPagination()
    {
        $pagination = $this->createPaginationObject();

        if ($this->itemsPerPage > -1) {
            $pagination->setMaxPerPage($this->itemsPerPage);
        }

        $query = \Request::getInstance()->query;

        if ($query->has($this->getQueryPaginationPageParameter())) {
            $page = intval($query->get('ccm_paging_p'));
            $pagination->setCurrentPage($page);
        }

        return $pagination;
    }


    protected function createPaginationObject()
    {
        $u = new \User();
        
        $adapter = new DoctrineDbalAdapter($this->deliverQueryObject(), function ($query) {
            $query->select("count(distinct event.grouped)");
            $query->resetQueryParts(array('groupBy'));
            $query->setMaxResults(1);
            //print $query->getSQL();
        });
        $pagination = new EventPagination($this, $adapter);

        return $pagination;
    }


    /**
     * @param $queryRow
     * @return \Concrete\Core\File\File
     */
    public function getEventResult($queryRow)
    {
        $c = Event::getByID($queryRow['cID'], 'ACTIVE');
        if (is_object($c) && $this->checkPermissions($c)) {
            if ($this->pageVersionToRetrieve == self::PAGE_VERSION_RECENT) {
                $cp = new \Permissions($c);
                if ($cp->canViewPageVersions()) {
                    $c->loadVersionObject('RECENT');
                }
            }

            $c->eID = $queryRow['eID'];
            $c->multidate = $queryRow['eventdate'] . ' ' . date('h:i A',strtotime($queryRow['eventstart'])) . ' ' . date('h:i A', strtotime($queryRow['eventend']));

            if (isset($queryRow['cIndexScore'])) {
                $c->setPageIndexScore($queryRow['cIndexScore']);
            }

            return $c;
        }
    }


    public function getEventResults()
    {
        $this->debugStart();

        $results = $this->executeGetResults();

        $this->debugStop();

        $ereturn = array();

        foreach ($results as $result) {
            $r = $this->getEventResult($result);

            if ($r != null) {
                $ereturn[] = $r;
            }
        }

        return $ereturn;
    }


    /**
     * Checks a particular day and returns true or false if an event exists.
     */
    public function eventIs($date, $category, $section = null, $allday = null)
    {
        $categories = explode(', ', $category);
        $category_q = '';
        $query_params = array();
        $i = 0;
       
        if (!in_array('All Categories', $categories)) {
            foreach ($categories as $cat) {
                $cat = str_replace('&', '&amp;', $cat);
                if ($i) {
                    $category_q .= "OR ";
                } else {
                    $category_q .= "AND (";
                }
                $category_q .= "category LIKE '%$cat%' ";
                $i++;
            }
            $category_q .= ")";
        } else {
            $category_q = '';
        }

        if ($section != null) {
            $section = "AND section LIKE '%$section%'";
        } else {
            $section = '';
        }
        if ($allday != null) {
            $allday = "AND allday LIKE '%$allday%'";
        } else {
            $allday = '';
        }

        $db = Loader::db();

        $events = array();

        $q = "SELECT * FROM btProEventDates WHERE DATE_FORMAT(date,'%Y-%m-%d') = DATE_FORMAT('$date','%Y-%m-%d') $category_q $section $allday";
        

        $r = $db->query($q);

        $this->status = null;
        $stat_avail = null;

        $events = array();
        while ($row = $r->fetchrow()) {
            $events[] = $row;
            if ($row['status'] == 'available') {
                $stat_avail = 'available';
            }
            if (!$stat_avail && $row['status'] == 'booked') {
                $stat_avail = 'booked';
            }
        }

        $this->status = $stat_avail;

        if (count($events) > 0) {
            return true;
        } else {
            return false;
        }
    }


    public function getCalNum()
    {
        return $this->calNum;
    }


}