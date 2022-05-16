<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));

use Page as Page;
use Block as Block;
use \Concrete\Package\Proevents\Src\ProEvents\EventItem;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;

$dth = Loader::helper('form/datetimetime');
$nh = Loader::helper('navigation');
$request = \Request::getInstance();

if ($request->get('state')) {
    $c = Stack::getByID($request->get('cID'), $request->get('state'));
} else {
    $c = Stack::getByID($request->get('cID'));
}

$cal_num = 12;

$euro_cal = 0;

$date = time();
$day = 1;
$year = $request->get('year');
$month = $request->get('month');
$date = $year . '-' . $month . '-' . $day;

$month = $month - 1;

$sctID = $request->get('sctID');
$ctID = $request->get('ctID');
$bID = $request->get('bID');

print '<h1>' . $request->get('title') . '</h1>';

for ($mi = 0; $mi < $cal_num; $mi++) {

    if (($month + 1) > 12) {
        $month = 1;
        $year = $year + 1;
    } else {
        $month = $month + 1;
    }

    $date = $year . '-' . $month . '-' . $day;

    $days_in_month = cal_days_in_month(0, $month, $year);

    if ($sctID != 'All Sections') {
        $section = $sctID;
    }

    $eventify = new \Concrete\Package\Proevents\Controller\Helpers\Eventify;

    $b = Block::getByID($bID);
    $controller = $b->getController();

    $ctID = $controller->ctID;

    $events = $controller->getEvents($date);
    $el = $controller->el;

    $first_day = mktime(0, 0, 0, $month, 1, $year);

    $title = date('F', $first_day);

    $day_of_week = date('D', $first_day);

    switch ($day_of_week) {
        case t('Mon') :
            if ($euro_cal >= 1) {
                $blank = 0;
            } else {
                $blank = 1;
            }
            break;
        case t('Tue') :
            if ($euro_cal >= 1) {
                $blank = 1;
            } else {
                $blank = 2;
            }
            break;
        case t('Wed') :
            if ($euro_cal >= 1) {
                $blank = 2;
            } else {
                $blank = 3;
            }
            break;
        case t('Thu') :
            if ($euro_cal >= 1) {
                $blank = 3;
            } else {
                $blank = 4;
            }
            break;
        case t('Fri') :
            if ($euro_cal >= 1) {
                $blank = 4;
            } else {
                $blank = 5;
            }
            break;
        case t('Sat') :
            if ($euro_cal >= 1) {
                $blank = 5;
            } else {
                $blank = 6;
            }
            break;
        case t('Sun') :
            if ($euro_cal >= 1) {
                $blank = 6;
            } else {
                $blank = 0;
            }
            break;
    }

    print "<div class=\"event_cal_wrap\"><table  class='event_cal'>";
    print "<tr>";
    print "<th class='select'></th>";

    print "<th colspan=5 class='year' align='center'>$title $year</th>";

    print "<th></th></tr>";

    if ($euro_cal >= 1) {

        print '<tr class="header"><td>' . t('Mon') . '</td><td>' . t('Tue') . '</td><td>' . t('Wed') . '</td><td>' . t(
                'Thu'
            ) . '</td><td>' . t('Fri') . '</td><td>' . t('Sat') . '</td><td>' . t('Sun') . '</td></tr>';

    } else {

        print '<tr class="header"><td>' . t('Sun') . '</td><td>' . t('Mon') . '</td><td>' . t('Tue') . '</td><td>' . t(
                'Wed'
            ) . '</td><td>' . t('Thu') . '</td><td>' . t('Fri') . '</td><td>' . t('Sat') . '</td></tr>';

    }

    $day_count = 1;

    print "<tr>";

    while ($blank > 0) {
        print "<td class='cal_blank'></td>";
        $blank = $blank - 1;
        $day_count++;
    }

    $day_num = 1;

    while ($day_num <= $days_in_month) {

        $daynum = date('Y-m-d', strtotime($year . '-' . $month . '-' . $day_num));

        $ei = $el->eventIs($daynum, $ctID, $section);

        if (date('Y-m-d') == date('Y-m-d', strtotime($year . '-' . $month . '-' . $day_num))) {
            $daystyle = 'current';
        } else {
            $daystyle = 'day';
        }

        print '<td  valign="top" class="' . $daystyle . ' ' . $el->status . '">';
        print '<div class="cal_day">';

        $itemstyle = 'normal';

        if ($ei == true) {

            print '<div class="' . $itemstyle . ' daynum hasevent ' . $state . '">';
            print '<a  href="javascript:;" onClick="loadMyDialogDo(\'' . $day_num . '_' . $mi . '\');">';
            print $day_num;
            print '</a>';
            print '<div  class="myDialogContent' . $day_num . '_' . $mi . ' popup_events" style="display: none;">';

            $allday = null;

            foreach ($events as $date_string => $ep) {

                $dh = Loader::helper('form/datetimetime');

                $date_array = $dth->translate_from_string($ep->multidate);

                $eID = $ep->eID;
                $date = $date_array['date'];
                $time = $date_array['start'] . ' - ' . $date_array['end'];

                $title = $ep->getCollectionName();

                $url = $nh->getLinkToCollection($ep);

                $content = $ep->getCollectionDescription();

                $location = $ep->getAttribute('event_local');

                $color = $ep->getAttribute('category_color');

                $category = $ep->getAttribute('event_category');

                $allday = $ep->getAttribute('event_allday');
                //var_dump($date_string);
                //var_dump($daynum);
                if ($date == $daynum) {
                    $events_item = $day_num;
                }


                $i += 1;

                if ($events_item == $day_num) {

                    if ($allday == 1) {
                        $itemstyle = 'allday';
                    }

                    if ($ep->getCollectionAttributeValue('exclude_nav')) {
                        $url = 'javascript:;';
                    }
                    print '<br style="clear: both;" />';
                    if ($color) {
                        print '<div style="float: right; clear: both; background-color: ' . $color . ';" class="category_color">' . $category . '</div>';
                    }
                    print '<h4><a href="' . $url . '?eID=' . $eID . '">' . $title . '</a></h4>';
                    if ($location != '') {
                        print '<strong>' . $location . '</strong>';
                        print '<br/>';
                    }
                    print '<strong>';

                    if ($allday != 1) {
                        print  $time;
                    } else {
                        print  t('All Day');
                    }

                    print '</strong>';
                    print '<br style="clear: both;" />';

                }
                unset($events_item);
            }
            print '	</div>';
            print '</div>';
        } else {
            print '<div class="' . $itemstyle . ' daynum">';
            print $day_num;
            print '<div  class="myDialogContent' . $day_num . '_' . $mi . '" style="display: none;">';
        }

        print '</div>';
        print '</td>';

        $day_num++;
        $day_count++;


        if ($day_count > 7) {
            print "</tr><tr>";
            $day_count = 1;
        }
    }

    while ($day_count > 1 && $day_count <= 7) {
        print "<td class='cal_blank'> </td>";
        $day_count++;
    }

    print "</tr></table></div>";

}