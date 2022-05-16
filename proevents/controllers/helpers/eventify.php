<?php   namespace Concrete\Package\Proevents\Controller\Helpers;

use \Concrete\Core\User\User as User;
use \Concrete\Core\Block\BlockType\BlockType as BlockType;
use \Concrete\Package\Proevents\Src\ProEvents\EventItem;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;
use Loader;

class Eventify
{
    public function __construct()
    {

    }

    public function getSettings()
    {
        $db = Loader::db();
        $r = $db->execute("SELECT * FROM btProEventSettings");
        while ($row = $r->fetchrow()) {
            $settings = $row;
        }
        if (empty($settings)) {
            $settings = array();
        }
        return $settings;
    }


    public function getEventListVars($event)
    {
        $request = \Request::getInstance();
        $vars = array();
        $event_item = new EventItem($event, $request->get('eID'));
        $vars['eID'] = $event_item->getEventItemID();
        $vars['date'] = $event_item->getEventItemDate();
        $vars['times_array'] = $event_item->getEventItemTimes();
        $vars['next_dates_array'] = $event_item->getEventItemNextDates();
        $vars['status'] = $vars['next_dates_array'][0]->status;
        $vars['title'] = $event->getCollectionName();
        $vars['url'] = Loader::helper('navigation')->getLinkToCollection($event);
        if ($event->getCollectionAttributeValue('exclude_nav')) {
            $vars['url'] = 'javascript:;';
        }
        $vars['content'] = $event_item->getEventDescription();
        $vars['allday'] = $event->getAttribute('event_allday');
        $vars['grouped'] = $event->getAttribute('event_grouped');
        $vars['location'] = $event->getAttribute('event_local');
        $vars['category'] = sprintf($event->getAttribute('event_category'));
        $akct = CollectionAttributeKey::getByHandle('event_category');
        $vars['color'] = $akct->getAttributeType()->getController()->getColorValue($vars['category']);
        $vars['contact_name'] = $event->getAttribute('contact_name');
        $vars['contact_email'] = $event->getAttribute('contact_email');
        $vars['address'] = $event->getAttribute('address');
        $vars['recur'] = $event->getAttribute('event_recur');
        $vars['thru'] = $event->getAttribute('event_thru');
        $imgHelper = Loader::helper('image');
        $vars['imageF'] = $event->getAttribute('thumbnail');
        if ($vars['imageF']) {
            $vars['image'] = $imgHelper->getThumbnail($vars['imageF'], 110, 85)->src;
        }
        return $vars;
    }

    public function getEventVars($c)
    {
        $request = \Request::getInstance();
        $vars = array();
        $vars['u'] = new User();
        $settings = $this->getSettings();
        $vars['settings'] = $settings;
        $vars['eventTitle'] = $c->getCollectionName();
        $vars['eventDate'] = $c->getCollectionDatePublic($settings['date_format']);
        $vars['location'] = $c->getAttribute('event_local');
        $vars['color'] = $c->getAttribute('category_color');
        $vars['category'] = $c->getAttribute('event_category');
        $vars['contact_name'] = $c->getAttribute('contact_name');
        $vars['contact_email'] = $c->getAttribute('contact_email');
        $vars['address'] = $c->getAttribute('address');
        /* function for grabbing all related attributes */
        $atts = $c->getSetCollectionAttributes();
        foreach ($atts as $attribute) {
            $value = $c->getCollectionAttributeValue($attribute);
            $handle = $attribute->akHandle;
            $vars[$handle] = $value;
        }
        $event_item = new EventItem($c, $request->get('eID'));
        $vars['n'] = $event_item->getEventItemsNum();
        $vars['next_dates_array'] = $event_item->getEventItemNextDates();
        return $vars;
    }


    public function checkDateExclude($eID)
    {
        $db = Loader::db();
        $r = $db->Query("SELECT * FROM btProEventDatesExclude WHERE eventID = $eID");
        if ($r->RecordCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getExcludedDates()
    {
        $db = Loader::db();
        $excluded = array();
        $r = $db->Query("SELECT * FROM btProEventDatesExclude");
        while ($row = $r->fetchrow()) {
            $excluded[] = $row['eventID'];
        }
        return $excluded;
    }

    public function getSearchEvents($search, $type)
    {
        $db = Loader::db();
        $events = array();
        switch ($type) {
            case 'title':
                $r = $db->Query(
                    "SELECT * FROM btProEventDates WHERE title LIKE '%?%' AND date >= CURDATE() ORDER BY date"
                ,array($search));
                break;
            case 'date':
                $date = date('Y-m-d', strtotime($search));
                $r = $db->Query("SELECT * FROM btProEventDates WHERE date = DATE_FORMAT('?','%Y-%m-%d')",array($date));
                break;
            case 'description':
                $r = $db->Query(
                    "SELECT * FROM btProEventDates WHERE description LIKE '%?%' AND date >= CURDATE() ORDER BY date"
                ,array($search));
                break;
        }
        while ($row = $r->fetchrow()) {
            $events[] = $row;
        }
        return $events;
    }

    public function getAllEvents()
    {
        $db = Loader::db();
        $events = array();
        $r = $db->Query("SELECT * FROM btProEventDates WHERE date >= CURDATE() GROUP BY eventID,grouped ORDER BY date");
        while ($row = $r->fetchrow()) {
            $events[] = $row;
        }
        return $events;
    }


    public function getEvent($eID)
    {
        $db = Loader::db();
        $r = $db->execute("SELECT * FROM btProEventDates WHERE eID = ?", array($eID));
        while ($row = $r->fetchrow()) {
            $date = $row;
        }
        return $date;
    }

    public function userSaved($cID = null, $uID = null)
    {
        $db = loader::db();
        $seID = $db->getOne("SELECT ueID FROM btProEventUserSaved WHERE eventID = ? AND uID =?", array($cID, $uID));
        if ($seID) {
            return true;
        }
        return false;
    }

    public function updateDate($vars)
    {
        $db = Loader::db();
        //first get the event.ID and group;
        $row = $db->getRow("SELECT * FROM btProEventDates WHERE eID = ?", array($vars[5]));
        $vals = array($vars[0], $vars[1], $vars[2], $vars[3], $vars[4], $row['eventID'], $row['grouped']);
        $r = $db->execute(
            "UPDATE btProEventDates SET title=?,description=?,status=?,event_price=?,event_qty=? WHERE eventID=? AND grouped=?",
            $vals
        );
    }


    public function getEventCats()
    {
        $db = Loader::db();
        $akID = $db->getOne("SELECT akID FROM AttributeKeys WHERE akHandle = 'event_category'");
        $categories = $db->getAll("SELECT value FROM atEventCategoryOptions WHERE akID = ?",array($akID));
        if (empty($categories)) {
            $categories = array();
        }
        return $categories;
    }

    public function getRawEventID($cID, $date, $sttime, $entime)
    {
        $start = strtoupper(date('g:i a', strtotime($sttime)));
        $end = strtoupper(date('g:i a', strtotime($entime)));
        $db = loader::db();
        $eventID = $db->getOne(
            "SELECT eID FROM btProEventDates WHERE eventID = ? AND date = '?' AND DATE_FORMAT(sttime,'%l:%i %p') = '?' AND DATE_FORMAT(entime,'%l:%i %p') = '?'"
        ,array($cID,$date,$start,$end));
        return $eventID;
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
}