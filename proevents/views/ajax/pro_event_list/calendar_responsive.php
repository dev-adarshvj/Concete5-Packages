<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));

use \Concrete\Package\Proevents\Src\ProEvents\EventItem;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;

$eventify = new \Concrete\Package\Proevents\Controller\Helpers\Eventify;
$nh = Loader::helper('navigation');
$th = Loader::helper('form/time');
$request = \Request::getInstance();

if ($request->get('state')) {
    $c = Page::getByID($request->get('cID'), $request->get('state'));
} else {
    $c = Page::getByID($request->get('cID'));
}

$link = $nh->getLinkToCollection($c);

$euro_cal = 0;


$date = time();
$day = 1;
$year = $request->get('year');
$month = $request->get('month');

$date = $year . '-' . $month . '-' . $day;


$bID = $request->get('bID');
Loader::model('block');
$b = Block::getByID($bID);
$controller = $b->getController();
$sctID = $controller->sctID;
if ($request->get('ctID') != '') {
    $ctID = $request->get('ctID');
    $controller->ctID = $ctID;
}
$controller->listType = 'responsive';
$settings = $controller->settings;


$days_in_month = cal_days_in_month(0, $month, $year);

if ($sctID != 'All Sections') {
    $section = $sctID;
}

$events = $controller->getEvents($date);

$el = $controller->el;

$ctID = $controller->ctID;

$first_day = mktime(0, 0, 0, $month, 1, $year);

$title = date('F', $first_day);

$day_of_week = date('D', $first_day);

print "<div class=\"title_year\"><h3>" . t($title) . " $year</h3></div>";

switch ($day_of_week) {
    case 'Mon' :
        if ($euro_cal >= 1) {
            $blank = 0;
        } else {
            $blank = 1;
        }
        break;
    case 'Tue' :
        if ($euro_cal >= 1) {
            $blank = 1;
        } else {
            $blank = 2;
        }
        break;
    case 'Wed' :
        if ($euro_cal >= 1) {
            $blank = 2;
        } else {
            $blank = 3;
        }
        break;
    case 'Thu' :
        if ($euro_cal >= 1) {
            $blank = 3;
        } else {
            $blank = 4;
        }
        break;
    case 'Fri' :
        if ($euro_cal >= 1) {
            $blank = 4;
        } else {
            $blank = 5;
        }
        break;
    case 'Sat' :
        if ($euro_cal >= 1) {
            $blank = 5;
        } else {
            $blank = 6;
        }
        break;
    case 'Sun' :
        if ($euro_cal >= 1) {
            $blank = 6;
        } else {
            $blank = 0;
        }
        break;
}

?>
<div class="calendar">
<?php   if ($euro_cal >= 1) { ?>
    <ul class="weekdays">
        <li><?php   echo t('Monday') ?></li>
        <li><?php   echo t('Tuesday') ?></li>
        <li><?php   echo t('Wednesday') ?></li>
        <li><?php   echo t('Thursday') ?></li>
        <li><?php   echo t('Friday') ?></li>
        <li><?php   echo t('Saturday') ?></li>
        <li><?php   echo t('Sunday') ?></li>
    </ul>
<?php   } else { ?>
    <ul class="weekdays">
        <li><?php   echo t('Sunday') ?></li>
        <li><?php   echo t('Monday') ?></li>
        <li><?php   echo t('Tuesday') ?></li>
        <li><?php   echo t('Wednesday') ?></li>
        <li><?php   echo t('Thursday') ?></li>
        <li><?php   echo t('Friday') ?></li>
        <li><?php   echo t('Saturday') ?></li>
    </ul>
<?php   } ?>
<?php  
$day_count = 1;

print '<ul class="days">';

while ($blank > 0) {
    print '<li class="calendar-day date_fill"><div class="date"></div></li>';
    $blank = $blank - 1;
    $day_count++;
}

$day_num = 1;

while ($day_num <= $days_in_month) {

    $daynum = date('Y-m-d', strtotime($year . '-' . $month . '-' . $day_num));

    $ei = $el->eventIs($daynum, $ctID, $section);
    
    //var_dump($daynum.' - '.$ctID.' - '.$section);

    print '<li class="calendar-day ' . $el->status . '">';

    if (date('Y-m-d') == date('Y-m-d', strtotime($year . '-' . $month . '-' . $day_num))) {
        $daystyle = 'current';
    } else {
        $daystyle = 'day';
    }

    print '<div class="date"><span class="day">' . date(
            'D',
            strtotime($year . '-' . $month . '-' . $day_num)
        ) . ',</span> <span class="month">' . date(
            'M',
            strtotime($year . '-' . $month . '-' . $day_num)
        ) . '</span> ' . $day_num . '</div>';

    $daydo = false;

    if ($ei || $daydo == true) {


        print '<div class="daynum hasevent">';


        print '<div class="infoPreview myDialogContent' . $day_num . '">';

        foreach ($events as $ep) {

            $i += 1;

            $dh = Loader::helper('form/datetimetime');
            $date_array = $dh->translate_from_string($ep->multidate);

            $event_obj = new EventItem($ep);

            $eID = $ep->eID;
            $date = $date_array['date'];
            $time = $th->formatTime($date_array['start']) . ' - ' . $th->formatTime($date_array['end']);

            $title = $ep->getCollectionName();

            $url = $nh->getLinkToCollection($ep);

            $content = $event_obj->getEventDescription();

            $location = $ep->getAttribute('event_local');

            $category = $ep->getAttribute('event_category');

            $akct = CollectionAttributeKey::getByHandle('event_category');
            $tcvalue = $ep->getAttributeValueObject($akct);
            $color = $akct->getAttributeType()->getController()->getColorValue($category);

            //var_dump($category);

            $allday = $ep->getAttribute('event_allday');

            if ($date == $daynum) {
                $events_item = $day_num;
            }

            if ($events_item == $day_num) {

                if ($ep->getCollectionAttributeValue('exclude_nav')) {
                    $url = 'javascript:;';
                }
                $url = $url . '?eID=' . $eID;
    

                if ($allday != 1) {
                    $itemstyle = 'normal';
                } else {
                    $itemstyle = 'allday';
                }

                print '<a  href="' . $url . '" data-rel="' . $i . '" class="eventtooltip">';
                if (!$color) {
                    $color = '989898';
                }
                print '<div style="background-color: #' . $color . '!important;" class="category_color">';
                print  $title;
                print '</div>';

                print '</a>';
                print '<div class="' . $itemstyle . ' show-info popup_' . $i . '" style="display: none;" data-url="' . $url . '">';
                print '<h3>' . $title . '</h3>';
                if ($location != '') {
                    print '<strong>' . $location . '</strong>';
                    print '<br/>';
                }
                print '<strong>';
                if ($allday != 1) {
                    print $time;
                } else {
                    print t('All Day');
                }

                print '</strong>';
                print '<br/>';

                if ($controller->truncateSummaries) {
                    print  '<p>' . substr($content, 0, $controller->truncateChars) . '…..</p>';
                } else {
                    print  '<p>' . $content . '</p>';
                }
                print '</div>';
            }
            unset($events_item);
        }
        print '	</div>';
        print '</div>';
    }

    print '</li>';

    $day_num++;
    $day_count++;


    if ($day_count > 7) {
        print "</ul><ul class=\"days\">";
        $day_count = 1;
    }
}


while ($day_count > 1 && $day_count <= 7) {
    print '<li class="calendar-day date_fill"><div class="date"></div></li>';
    $day_count++;
}
print '</ul>';
print '</div>';

print '<script type="text/javascript">
$(function(){
	 $(".eventtooltip").each(function(){
	 	$(this).mouseover(function(){
	 		$(\'.show-info\').hide();
	 		var rel = $(this).attr("data-rel");
	 		if($(\'.popup_\'+rel).css(\'display\') != \'block\'){
	        	$(\'.popup_\'+rel).show();
	        	$(\'.popup_\'+rel).mouseout(function(){
		      		$(\'.popup_\'+rel).hide();
		      	});
		    
	        	$(\'.popup_\'+rel).bind(\'click tap\',function(){
	        		window.location = $(this).attr(\'data-url\');
	        	});     
	        }
        });
     });
});


	/**
	 * Equal Heights Plugin
	 * Equalize the heights of elements. Great for columns or any elements
	 * that need to be the same size (floats, etc).
	 * 
	 * Version 1.0
	 * Updated 12/10/2008
	 *
	 * Copyright (c) 2008 Rob Glazebrook (cssnewbie.com) 
	 *
	 * Usage: $(object).equalHeights([minHeight], [maxHeight]);
	 * 
	 * Example 1: $(".cols").equalHeights(); Sets all columns to the same height.
	 * Example 2: $(".cols").equalHeights(400); Sets all cols to at least 400px tall.
	 * Example 3: $(".cols").equalHeights(100,300); Cols are at least 100 but no more
	 * than 300 pixels tall. Elements with too much content will gain a scrollbar.
	 * 
	 */
	
	(function($) {
		$.fn.equalHeights = function(minHeight, maxHeight) {
			tallest = (minHeight) ? minHeight : 0;
			this.each(function() {
				if($(this).height() > tallest) {
					tallest = ($(this).height())*1 + 10;
				}
			});
			if((maxHeight) && tallest > maxHeight) tallest = maxHeight;
			return this.each(function() {
				$(this).height(tallest).css("overflow","auto");
			});
		}
	})(jQuery);
	
	$(".calendar-day").equalHeights(150,600);
</script>';