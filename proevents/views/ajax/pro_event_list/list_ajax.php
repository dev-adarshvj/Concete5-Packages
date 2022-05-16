<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));

use \Concrete\Package\Proevents\Src\ProEvents\EventItem;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;
use Page as Page;
use Block as Block;
use Request as Request;

$eventify = new \Concrete\Package\Proevents\Controller\Helpers\Eventify;
$dth = Loader::helper('form/datetimetime');
$th = Loader::helper('form/time');
$nh = Loader::helper('navigation');
$request = \Request::getInstance();

$bID = $request->get('bID');
$cID = $request->get('ccID');

$b = Block::getByID($bID);

$controller = $b->getController();

if ($cID) {
    $controller->page = $cID;
    $controller->paginationPage = $request->get('ccm_paging_p') ? $request->get('ccm_paging_p') : 1;
}

if ($request->get('type')) {
    $controller->listType = $request->get('type');
}

if ($request->get('category')) {
    $controller->category = $request->get('category');
}

$settings = $controller->settings;

$events = $controller->getEvents($request->get('date'));
$truncateChars = $controller->truncateChars;

foreach ($events as $date_string => $event) {

    extract($eventify->getEventListVars($event));

    $event_item = new EventItem($event);

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
    //###################################################################################//
    //here we lay out they way the page looks, html with all our vars fed to it	     //
    //this is the content that displays.  it is recommended not to edit anything beyond  //
    //the content parse.  Feel free to structure and re-arrange any element and adjust   //
    //CSS as desired.							 	     //
    //###################################################################################//
    ?>
    <div class="smallcal">
        <div class="calwrap">
            <div class="img">
                <div class="month" <?php   if ($color) {
                    echo 'style="background-color: #' . $color . '!important;"';
                } ?>>
                    <?php  
                    $date_month = date('M', strtotime($date));
                    echo $months[$date_month];
                    ?>
                </div>
                <div class="day">
                    <?php   echo date('d', strtotime($date)); ?>
                </div>
            </div>
        </div>
        <div class="infowrap">
            <div class="titlehead">
                <?php  
                if ($color) {
                    print '<div style="background-color: #' . $color . ';" class="category_color">' . $category . '</div>';
                }
                ?>
                <div class="title">
                    <?php  
                    echo '<a href="' . $url . '?eID=' . $eID . '">' . $title . '</a>';
                    if ($status) {
                        echo '<span class="' . $status . '"></span>';
                    }
                    ?>
                </div>
                <div class="local">
                    <?php   echo $location; ?>
                </div>
                <div class="time">
                    <?php  
                    if (is_array($next_dates_array)) {
                        foreach ($next_dates_array as $next_date) {
                            echo date(t('M dS '), strtotime($next_date->date));
                            if ($recur == 'daily' && $grouped) {
                                echo t(' - ') . date(t('M dS '), strtotime($thru));
                            }
                            if ($allday != 1) {
                                echo $th->formatTime($next_date->start) . ' - ' . $th->formatTime($next_date->end) . '<br/>';
                            } else {
                                echo ' - ' . t('All Day') . '<br/>';
                            }
                        }
                    }
                    ?>
                </div>
            </div>
            <div class="description">
                <?php  
                if ($truncateChars) {
                    print  substr($content, 0, $truncateChars) . '.....';
                } else {
                    print  $content;
                }
                ?>
            </div>
            <div class="eventfoot">
                <?php  
                if ($contact_email != '') {
                    ?>
                    <a href="mailto:<?php   echo $contact_email; ?>"><?php   echo t(
                            'contact: '
                        ); ?><?php   echo $contact_name ?></a>
                <?php   }
                if ($contact_email != '' && $address != '') { ?> || <?php   }
                if ($address != '') { ?><a
                    href="http://maps.google.com/maps?f=q&amp;hl=en&amp;&saddr=<?php   echo $address; ?>"
                    target="_blank"> <?php   echo t('get directions') ?></a> <?php   } ?>
            </div>
        </div>
    </div>
    <?php  
    //#####################################################################################//
    //this is the end of the recommended content area.  please do not edit below this line //
    //#####################################################################################//

    $dateP = $date;
}

if (count($events) < 1) {
    print 'No events at this time.';
}

$el = $controller->el;

if ($controller->showPagination){
    echo $controller->pagination;
}

