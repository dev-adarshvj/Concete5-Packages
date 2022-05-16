<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));

use Page as Page;
use Block as Block;
use \Concrete\Package\Proevents\Src\ProEvents\EventItem;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;

function rssInfo($bID)
{
    Loader::model('block');
    $b = Block::getByID($bID);
    getFeed($b);
}


function getFeed($b)
{
    $eventify = new \Concrete\Package\Proevents\Controller\Helpers\Eventify;
    $settings = $eventify->getSettings();

    if($b->getBlockTypeHandle() != 'pro_event_list'){
        $bt = BlockType::getByHandle('pro_event_list');
        $controller = $bt->getController();
    }else{
        $controller = $b->getController();
    }

    $controller->isPaged = false;

    if (!$settings['tz_format']) {
        $settings['tz_format'] = 'US/Eastern';
    }

    if ($_GET['eID']) {
        $controller->setListType('Specific');
        $eID = $_GET['eID'];
    } else {
        $controller->setListType('AllDates');
        $eID = null;
    }

    $events = $controller->getEvents($eID);

    foreach ($events as $date_string => $event) {

        $dh = Loader::helper('form/datetimetime');
        $date_array = $dh->translate_from_string($event->multidate);

        $date = $date_array['date'];
        $start = $date_array['start'];
        $end = $date_array['end'];
        $allday = $event->getAttribute('event_allday');
        $title = $event->getCollectionName();
        $block = $event->getBlocks('Main');
        $content = $event->getCollectionDescription();

        echo "\nBEGIN:VEVENT\n";
        echo "UID:" . rand(1, 25000000) . "\n";
        echo "DTSTAMP:" . date('Ymd') . "T" . date('Hms') . "Z\n";
        if ($allday == 1) {
            echo "DTSTART;VALUE=DATE:" . date('Ymd', strtotime($date)) . "\n";
            echo "DTEND;VALUE=DATE:" . date('Ymd', strtotime($date)) . "\n";
        } else {
            echo "DTSTART;TZID=" . $settings['tz_format'] . ":" . date('Ymd', strtotime($date)) . "T" . date(
                    'Hi',
                    strtotime($start)
                ) . "00\n";
            echo "DTEND;TZID=" . $settings['tz_format'] . ":" . date('Ymd', strtotime($date)) . "T" . date(
                    'Hi',
                    strtotime($end)
                ) . "00\n";
        }
        echo "SUMMARY:" . $title . "\n";
        //echo 		"URL:".BASE_URL.DIR_REL.Loader::helper('navigation')->getLinkToCollection(Page::getByID($row['eventID'])) ."Z\n";
        echo "DESCRIPTION:<![CDATA[" . str_replace(',', '\,', htmlspecialchars(strip_tags($content))) . "]]>\n";
        echo "END:VEVENT\n";
    }
}

header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename=ProEvent.ics');
echo "BEGIN:VCALENDAR\n";
echo "METHOD:PUBLISH\n";
echo "VERSION:2.0\n";
//echo "X-WR-CALNAME:SimpleEvent\n";
echo "PRODID:-//Apple Inc.//iCal 4.0.1//EN";

rssInfo($_GET['bID']);


echo "CALSCALE:GREGORIAN\n";
echo "END:VCALENDAR\n";
?> 