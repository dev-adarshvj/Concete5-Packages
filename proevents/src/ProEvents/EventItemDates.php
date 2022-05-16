<?php  
namespace Concrete\Package\Proevents\Src\ProEvents;

use \Concrete\Core\Legacy\Model;
use \Concrete\Core\Page\Page;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;
use Loader;
use Carbon\Carbon;
use Log;

/**
 *
 * An object that allows a filtered list of events to be returned.
 * @package ProEvents
 *
 **/
class EventItemDates extends model
{


    function __construct($p, $save = false)
    {

        $cID = $p->getCollectionID();

        $event = $p;

        //set the event object
        $this->event = $event;

        //set the event title
        $this->title = $event->getCollectionName();

        //set the event description
        $this->description = $event->getCollectionDescription();

        //set the allday
        $akad = CollectionAttributeKey::getByHandle('event_allday');
        $this->allday = $event->getCollectionAttributeValue($akad);

        //set grouped
        $akad = CollectionAttributeKey::getByHandle('event_grouped');
        $this->grouped = sprintf($event->getCollectionAttributeValue($akad));

        //set the dates array
        $dtt = Loader::helper('form/datetimetime');
        $dates_array = $dtt->translate_from($event);
        //var_dump($dates_array);exit;
        //Log::addEntry(json_encode($dates_array));

        if ($dates_array[1]['date'] != '') {
            sort($dates_array);
            $this->dates_array = $dates_array;

            //set event recuring
            $akrr = CollectionAttributeKey::getByHandle('event_recur');
            if ($event->getCollectionAttributeValue($akrr)) {
                $this->recur = $event->getCollectionAttributeValue($akrr)->current()->value;
            }


            //set event categories
            $aklc = CollectionAttributeKey::getByHandle('event_category');
            $eventCat_pre = $event->getCollectionAttributeValue($aklc);

            $this->category = $eventCat_pre;

            //set event location
            $aklo = CollectionAttributeKey::getByHandle('event_local');
            $this->location = $event->getCollectionAttributeValue($aklo);

            //set event section
            $sec = Page::getByID($event->getCollectionParentID());
            $this->section = $sec->getCollectionID();

            //set event price
            $price = CollectionAttributeKey::getByHandle('event_price');
            if ($event->getCollectionAttributeValue($price)) {
                $this->event_price = sprintf($event->getCollectionAttributeValue($price));
            } else {
                $this->event_price = null;
            }

            //set event qty
            $qty = CollectionAttributeKey::getByHandle('event_qty');
            if ($event->getCollectionAttributeValue($qty)) {
                $this->event_qty = sprintf($event->getCollectionAttributeValue($qty));
            } else {
                $this->event_qty = null;
            }


            $this->eventID = $event->getCollectionID();

            if ($this->recur != '') {
                $this->dateSet();
            } else {
                //$dates = array($c->getCollectionDatePublic('Y-m-d'));

                for ($i = 0; $i < count($this->dates_array); $i++) {
                    $dates[] = array(
                        array(
                            'dsID' => $this->dates_array[$i]['dsID'],
                            'date' => $this->dates_array[$i]['date']
                        )
                    );
                }

                $this->dates = $dates;
            }


            //var_dump($this->dates);exit;
            if ($save == true) {
                $this->saveEventItemDates();
            }
        }
    }

    private function dateSet()
    {
        $iti = null;$wc=null;
        $cobj = $this->event; $recur = $this->recur; Loader::model("attribute/categories/collection"); $emdd = CollectionAttributeKey::getByHandle('event_multidate'); $date_multi = $cobj->getCollectionAttributeValue($emdd); $date_multi_array = explode(':^:', $date_multi); foreach ($date_multi_array as $dated) { $date_sub = explode(' ', $dated); $dates_array[] = $date_sub[0]; } sort($dates_array); $excluded_dates = array(); $eexc = CollectionAttributeKey::getByHandle('event_exclude'); $date_exclude = $cobj->getCollectionAttributeValue($eexc); $date_exclude_array = explode(':^:', $date_exclude); $excluded_dates = $date_exclude_array; $esst = new Carbon($dates_array[0]); $ess = $esst->format('Y-m-d'); $evth = CollectionAttributeKey::getByHandle('event_thru'); $eet = new Carbon($cobj->getCollectionAttributeValue($evth)); $ee = $eet->format('Y-m-d'); $d1m = date('n', strtotime($cobj->getCollectionAttributeValue($evth))); $d1d = date('j', strtotime($cobj->getCollectionAttributeValue($evth))); $d1y = date('Y', strtotime($cobj->getCollectionAttributeValue($evth))); $d2m = date('n', strtotime($ess)); $d2d = date('j', strtotime($ess)); $d2y = date('Y', strtotime($ess)); $datetime1 = mktime(0, 0, 0, $d1m, $d1d, $d1y); $datetime2 = mktime(0, 0, 0, $d2m, $d2d, $d2y); $interval = floor(($datetime1 - $datetime2) / 86400); $diff = $eet->diff($esst); $dayspan = $diff->days; /*$dayspan = $interval;*/ $wk = true; for ($d = 0; $d <= $dayspan; $d += 1) { $iti++; $year = $cobj->getCollectionDatePublic('Y'); $month = $cobj->getCollectionDatePublic('m'); $day = $cobj->getCollectionDatePublic('d'); $daynum = date('Y-m-d', strtotime($esst)); if (!in_array($daynum, $excluded_dates)) { if ($recur == t('daily')) { $di = 0; foreach ($dates_array as $esd) { $di++; $dates[$di][$iti]['date'] = $daynum; $dates[$di][$iti]['dsID'] = $di; } } elseif ($recur == t('weekly')) { $di = 0; foreach ($dates_array as $esd) { $di++; $es = date('Y-m-d', strtotime($esd)); $eventDd = date('D', strtotime($es)); $daynumD = date('D', strtotime($daynum)); if ($daynumD == $eventDd) { $dates[$di][$iti]['date'] = $daynum; $dates[$di][$iti]['dsID'] = $di; } } } elseif ($recur == t('every other week')) { $di = 0; foreach ($dates_array as $esd) { $di++; $es = date('Y-m-d', strtotime($esd)); $eventDd = date('D', strtotime($es)); $daynumD = date('D', strtotime($daynum)); if ($daynumD == $eventDd && $wk == true) { $dates[$di][$iti]['date'] = $daynum; $dates[$di][$iti]['dsID'] = $di; $wc++; if ($wc == count($dates_array)) { $wk = false; $wc = 0; } } elseif ($daynumD == $eventDd && $wk == false) { $wc++; if ($wc == count($dates_array)) { $wk = true; $wc = 0; } } } } elseif ($recur == t('monthly')) { $daynumm = date('Y-m', strtotime($daynum)); $daynummDay = date('d', strtotime($daynum)); $bug_array = array('01', '08', '15', '22', '29'); $di = 0; foreach ($dates_array as $esd) { $di++; $es = date('Y-m-d', strtotime($esd)); $esi = date('Y-m-d', strtotime($esd)); $eventm = date('Y-m', strtotime($es)); $eventD = date('d', strtotime($es)); $eventDa = date('D', strtotime($es)); $eventDaL = date('l', strtotime($es)); $eventFirstDay = date('Y-m-d', strtotime($eventm . '-01')); $eventDa = date('D', strtotime($eventFirstDay)); $monthFirstDay = date('Y-m-d', strtotime($daynumm . '-01')); $monthD = date('d', strtotime($monthFirstDay)); $monthDa = date('D', strtotime($monthFirstDay)); $eventFirstDay = date('Y-m-d', strtotime($eventm . '-01')); $em = date('m', strtotime($monthFirstDay)); $bug_first = 'first '; $bug_second = 'second '; $bug_third = 'third '; $bug_fourth = 'fourth '; $bug_fifth = 'fifth '; if (in_array($daynummDay, $bug_array) && !in_array($eventD, $bug_array)) { if ($daynummDay == '01') { $bug_first = '+0 week '; $bug_second = 'first '; $bug_third = 'second '; $bug_fourth = 'third '; $bug_fifth = 'fourth '; $es = date('Y-m-d', strtotime('+0 week ', strtotime($es))); } else { $bug_first = 'first '; $bug_second = 'second '; $bug_third = 'third '; $bug_fourth = 'fourth '; $bug_fifth = 'fifth '; $es = date('Y-m-d', strtotime('-1 week ', strtotime($es))); } } if (in_array($eventD, $bug_array) && !in_array($daynummDay, $bug_array)) { $es = date('Y-m-d', strtotime('+1 week ', strtotime($es))); } elseif (in_array($eventD, $bug_array) && $daynummDay == '01') { $bug_first = '+0 week '; $es = date('Y-m-d', strtotime('+0 week ', strtotime($es))); } if ($es == date('Y-m-d', strtotime($bug_first . $eventDaL . '', strtotime($eventFirstDay)))) { if ($daynum == date( 'Y-m-d', strtotime($bug_first . $eventDaL . '', strtotime($monthFirstDay)) ) ) { $dates[$di][$iti]['date'] = $daynum; $dates[$di][$iti]['dsID'] = $di; } } elseif ($es == date( 'Y-m-d', strtotime($bug_second . $eventDaL . '', strtotime($eventFirstDay)) ) ) { if ($daynum == date( 'Y-m-d', strtotime($bug_second . $eventDaL . '', strtotime($monthFirstDay)) ) ) { $dates[$di][$iti]['date'] = $daynum; $dates[$di][$iti]['dsID'] = $di; } } elseif ($es == date( 'Y-m-d', strtotime($bug_third . $eventDaL . '', strtotime($eventFirstDay)) ) ) { if ($daynum == date( 'Y-m-d', strtotime($bug_third . $eventDaL . '', strtotime($monthFirstDay)) ) ) { $dates[$di][$iti]['date'] = $daynum; $dates[$di][$iti]['dsID'] = $di; } } elseif ($es == date( 'Y-m-d', strtotime($bug_fourth . $eventDaL . '', strtotime($eventFirstDay)) ) ) { if ($daynum == date( 'Y-m-d', strtotime($bug_fourth . $eventDaL . '', strtotime($monthFirstDay)) ) ) { $dates[$di][$iti]['date'] = $daynum; $dates[$di][$iti]['dsID'] = $di; } } elseif ($es == date( 'Y-m-d', strtotime($bug_fifth . $eventDaL . '', strtotime($eventFirstDay)) ) ) { if ($daynum == date( 'Y-m-d', strtotime($bug_fifth . $eventDaL . '', strtotime($monthFirstDay)) ) ) { $dates[$di][$iti]['date'] = $daynum; $dates[$di][$iti]['dsID'] = $di; } } } } elseif ($recur == t('yearly')) { $di = 0; foreach ($dates_array as $esd) { $di++; $es = date('Y-m-d', strtotime($esd)); $daynumy = date('m-d', strtotime($daynum)); $eventy = date('m-d', strtotime($es)); if ($daynumy == $eventy) { $dates[$di][$iti]['date'] = $daynum; $dates[$di][$iti]['dsID'] = $di; } } } } $esst->addDays(1); } $this->dates = $dates;
        //exit;
    }


    public function saveEventItemDates()
    {
        $db = Loader::db();
        $request = \Request::getInstance();
        $g = 0;

        foreach ($this->dates as $date_days) {

            $t = 0;
            foreach ($date_days as $date) {
                if ($this->recur != 'daily') {
                    $t++;
                }
                foreach ($this->dates_array as $da) {

                    if ($da['dsID'] == $date['dsID']) {
                        if ($this->grouped < 1) {
                            $g++;
                        } else {
                            $g = $t;
                        }

                        $edate = $date['date'];
                        $start = date('H:i:s', strtotime($da['start']));
                        $end = $da['end'];

                        $generated = $db->getRow(
                            "SELECT eID,status,event_qty from btProEventDates where eventID = ? AND date=? AND sttime=?",
                            array($this->eventID, $edate, $start)
                        );

                        $eID = $generated['eID'];

                        $args = array(
                            'title' => $this->title,
                            'category' => $this->category,
                            'section' => $this->section,
                            'eventID' => $this->eventID,
                            'date' => $edate,
                            'allday' => $this->allday,
                            'sttime' => $start,
                            'entime' => date('H:i:s', strtotime($end)),
                            'description' => $this->description,
                            'status' => $request->get('status'),
                            'location' => $this->location,
                            'grouped' => $g,
                            'additional_data' => '',
                            'updated' => 1,
                            'event_qty' => $this->event_qty,
                            'event_price' => $this->event_price,
                        );


                        if ($args) {
                            if ($eID) {
                                if ($request->get('status') == '') {
                                    $args['status'] = $generated['status'];
                                    $args['event_qty'] = $generated['event_qty'];
                                }
                                $db->update('btProEventDates', $args, array('eID' => $eID));
                            } else {
                                $db->insert('btProEventDates', $args);
                            }
                        }

                    }

                }

            }
        }
        $eventID = $this->eventID;
        $db->Execute("DELETE from btProEventDates where eventID = ? AND updated <> ?", array($this->eventID, 1));
        $db->Execute("UPDATE btProEventDates SET updated = ? where eventID = ?", array(0, $this->eventID));
    }


    public function getEventDates()
    {
        return $this->dates;
    }

}