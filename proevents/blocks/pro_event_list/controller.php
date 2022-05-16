<?php  
namespace Concrete\Package\Proevents\Block\ProEventList;

use \Concrete\Core\Block\BlockController;
use Loader;
use \Concrete\Package\Proevents\Src\ProEvents\EventList as EventList;
use \Concrete\Core\Block\BlockType\BlockType as BlockType;
use \Concrete\Core\Block\Block as Block;
use Config;
use Page;
use User;
use View;
use Request;

class Controller extends BlockController
{

    public $pobj;
    public $el;
    public $settings;
    public $ccID;
    public $paginationPage;
    public $listType;
    public $category;

    protected $btTable = 'btProEventList';
    protected $btInterfaceWidth = "400";
    protected $btInterfaceHeight = "430";

    protected $btCacheBlockOutput = false;
    protected $btCacheBlockRecord = true;


    public function getBlockTypeDescription()
    {
        return t("Event List.");
    }

    public function getBlockTypeName()
    {
        return t("Event List");
    }

    function getbID()
    {
        return $this->bID;
    }

    public function setListType($listType)
    {
        $this->listType = $listType;
    }

    function getEvents($date = null, $date2 = null)
    {

        $c = Page::getCurrentPage();

        if ($this->bID) {
            $db = Loader::db();
            $q = "select num, ordering, rssTitle, nonelistmsg, showfeed, rssDescription, truncateSummaries, isPaged, truncateChars, ctID, sctID from btProEventList where bID = '?'";
            $r = $db->query($q,array($this->bID));
            if ($r) {
                $row = $r->fetchRow();
            }
        } else {
            $row['num'] = $this->num;
            $row['ordering'] = $this->ordering;
            $row['rssTitle'] = $this->rssTitle;
            $row['nonelistmsg'] = $this->nonelistmsg;
            $row['showfeed'] = $this->showfeed;
            $row['rssDescription'] = $this->rssDescription;
            $row['truncateSummaries'] = $this->truncateSummaries;
            $row['isPaged'] = $this->isPaged;
            $row['truncateChars'] = $this->truncateChars;
            $row['ctID'] = $this->ctID;
            $row['sctID'] = $this->sctID;
        }

        $request = \Request::getInstance();

        $el = new EventList();

        $el->setEventNum($this->num);

        if ($this->paginationPage) {
            $c = '/index.php/proevents/routes/list_ajax';
            $request->attributes->set('ccm_paging_p',$this->paginationPage);
        }

        $b = Block::getByID($this->bID);

        if ($this->listType) {
            $template = strtolower($this->listType);
            $el->setEventTemplate($template);
        } else {
            $template = strtolower($b->getBlockFilename());
            $el->setEventTemplate($template);
        }

        $el->filterDates($date, $date2);
        
        $el->setEventOrdering($this->ordering);

        if ($this->ctID != 'All Categories') {
            $selected_cat = explode(', ', $this->ctID);
            $el->filterByCategories($selected_cat);
        }elseif ($this->category) {
            $el->filterByCategories(array($this->category));
        }

        if ($this->sctID != 'All Sections' && $this->sctID != '') {
            $el->filterByParentID($this->sctID);
        }

        if ($this->filter_by_user) {
            $u = new User();
            $el->filterByUser($u->uID);
        }

        //$el->debug();

        //Pagination...
        $showPagination = false;
        $calNum = $el->getCalNum();
        if ($this->num > 0 && $calNum < 1 && $this->isPaged) {
            $pagination = $el->getEventPagination();
            $events = $pagination->getCurrentEventResults();
            if ($pagination->getTotalPages() > 1 && $this->isPaged) {
                $showPagination = true;
                $paginated = $pagination->renderDefaultView($c);
                $this->set('pagination', $paginated);
                $this->pagination = $paginated;
            }
        } elseif( $this->num > 0  && $calNum < 1) {
	        $pagination = $el->getEventPagination();
            $events = $pagination->getCurrentEventResults();
	    }else{
            $events = $el->getEventResults();
        }

        $this->set('showPagination', $showPagination);
        $this->showPagination = $showPagination;


        if ($showPagination) {
            $this->requireAsset('css', 'core/frontend/pagination');
        }

        $this->el = $el;
        $this->set('el', $el);

        return $events;
    }


    function view()
    {
        $c = Page::getCurrentPage();
        $eventify = new \Concrete\Package\Proevents\Controller\Helpers\Eventify;
        $this->set('eventify', $eventify);
        $this->set('settings', $eventify->getSettings());
        $this->set('ical_url', $this->getiCalUrl());
        $this->set('ical_img_url', $this->getiCalImgUrl());
        $this->set('rss_url', $this->getRssUrl());
        $this->set('rss_img_url', $this->getRssImgUrl());

        $eArray = array();
        $eArray = $this->getEvents();
        $this->set('eArray', $eArray);
        $this->set('message', $this->post('message'));
        $this->set('nh', loader::helper('navigation'));
        $this->set('dth', Loader::helper('form/datetimetime'));
        $this->set('link', Loader::helper('navigation')->getLinkToCollection($c));
        $months = array(
            'Jan' => t('Jan'),
            'Feb' => t('Feb'),
            'Mar' => t('Mar'),
            'Apr' => t('Apr'),
            'May' => t('May'),
            'Jun' => t('Jun'),
            'Jul' => t('Jul'),
            'Aug' => t('Aug'),
            'Sep' => t('Sep'),
            'Oct' => t('Oct'),
            'Nov' => t('Nov'),
            'Dec' => t('Dec'),
        );
        $this->set('months', $months);
    }


    public function getiCalUrl()
    {
        $uh = Loader::helper('concrete/urls');
        $bt = BlockType::getByHandle('pro_event_list');
        $rssUrl = $uh->getBlockTypeToolsURL($bt) . "/iCal.php";
        return $rssUrl;
    }


    public function getRssUrl()
    {
        $uh = Loader::helper('concrete/urls');
        $bt = BlockType::getByHandle('pro_event_list');
        $rssUrl = $uh->getBlockTypeToolsURL($bt) . "/rss.php";
        return $rssUrl;
    }

    public function getiCalImgUrl()
    {
        $uh = Loader::helper('concrete/urls');
        $bt = BlockType::getByHandle('pro_event_list');
        $iCalIconUrl = $uh->getBlockTypeAssetsURL($bt, '/images/calendar_sml.png');
        return $iCalIconUrl;
    }

    public function getRssImgUrl()
    {
        $uh = Loader::helper('concrete/urls');
        $bt = BlockType::getByHandle('pro_event_list');
        $iCalIconUrl = $uh->getBlockTypeAssetsURL($bt, '/images/rss.png');
        return $iCalIconUrl;
    }


    function save($data)
    {

        if (!$data['ctID'] || !is_array($data['ctID'])) {
            $data['ctID'] = array();
        }

        if (!in_array('All Categories', $data['ctID']) && !empty($data['ctID'])) {
            if (count($data['ctID']) > 1) {
                $eventCat = implode(', ', $data['ctID']);
            } else {
                $eventCat = $data['ctID'][0];
            }
        } else {
            $eventCat = 'All Categories';
        }


        $args['num'] = isset($data['num']) ? $data['num'] : '';
        $args['ordering'] = isset($data['ordering']) ? $data['ordering'] : '';
        $args['rssTitle'] = isset($data['rssTitle']) ? $data['rssTitle'] : '';
        $args['nonelistmsg'] = isset($data['nonelistmsg']) ? $data['nonelistmsg'] : '';
        $args['showfeed'] = isset($data['showfeed']) ? $data['showfeed'] : '';
        $args['listType'] = isset($data['listType']) ? $data['listType'] : '';
        $args['rssDescription'] = isset($data['rssDescription']) ? $data['rssDescription'] : '';
        $args['truncateSummaries'] = ($data['truncateSummaries'] == 1) ? 1 : 0;
        $args['isPaged'] = ($data['isPaged'] == 1) ? 1 : 0;
        $args['truncateChars'] = isset($data['truncateChars']) ? $data['truncateChars'] : '';
        $args['showfilters'] = ($data['showfilters'] == 1) ? 1 : 0;
        $args['ctID'] = $eventCat;
        $args['sctID'] = isset($data['sctID']) ? $data['sctID'] : '';
        $args['filter_by_user'] = ($data['filter_by_user'] == 1) ? 1 : 0;
        parent::save($args);
    }

    public function on_page_view()
    {
        $html = Loader::helper('html');


        $this->addHeaderItem($html->css('app.css'));
        $this->addHeaderItem($html->javascript('jquery.js'));
        $this->addHeaderItem($html->javascript('jquery-ui.js'));
        //http://concrete5.7.0/packages/proevents/blocks/pro_event_list/templates/calendar_jquery/js/gcal.js

        $b = Block::getByID($this->bID);
        $template = strtolower($b->getBlockFilename());

        if ($template == 'calendar_jquery') {
            $this->addHeaderItem($html->css('fullcalendar/fullcalendar.min.css', 'proevents'));
            $this->addHeaderItem($html->css('qtip/jquery.qtip.min.css', 'proevents'));

            $this->addFooterItem($html->javascript('fullcalendar/lib/moment.min.js', 'proevents'));
            $this->addFooterItem($html->javascript('fullcalendar/fullcalendar.min.js', 'proevents'));
            $this->addFooterItem($html->javascript('fullcalendar/gcal.js', 'proevents'));
            $this->addFooterItem($html->javascript('qtip/jquery.qtip.min.js', 'proevents'));
        }
    }

}

?>