<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));

use \URL;

$eventify = new \Concrete\Package\Proevents\Controller\Helpers\Eventify;
$dtt = Loader::helper('form/datetimetime');
$th = Loader::helper('form/time');
$c = Page::getCurrentPage();

$request = \Request::getInstance();
$eID = $request->get('eID');

extract($eventify->getEventVars($c));
extract($settings);

$imgHelper = Loader::helper('image');
$imageF = $c->getAttribute('thumbnail');
if ($imageF) {
    $image = $imgHelper->getThumbnail($imageF, 220, 220)->src;
}
?>
<div class="ccm-ui">
    <?php  
    if ($u->isLoggedIn() && $invites > 0) {
        ?>
        <a href="<?php  echo URL::to('/proevents/tools/invite')?>?ccID=<?php  echo  $c->getCollectionID() ?>&uID=<?php  echo  $u->getUserID() ?>"
           id="event_invite" alt="event_envite" class="dialog-launch btn btn-info invite" dialog-width="300"
           dialog-height="120" dialog-modal="true" dialog-title="Event Invite" dialog-on-close=""
           onClick="javascript:;"><?php   echo t('Invite Others'); ?></a>
    <?php  
    }
    ?>
    <?php  
    if ($u->isLoggedIn() && $user_events > 0) {
        if ($eventify->userSaved($c->getCollectionID(), $u->getUserID())) {
            $saved_status = true;
        }
        ?>
        <a href="<?php  echo URL::to('/proevents/tools/save_event')?>?event=<?php  echo  $c->getCollectionID() ?>&user=<?php   echo $u->getUserID(); ?>" class="save_event btn btn-success" data-status="<?php   if ($saved_status) {echo 'unsave';} else {echo 'save';} ?>">
        <?php   if ($saved_status) { echo t('Unsave'); } else { echo t('Save'); } ?><?php   echo t(' Event'); ?>
        </a>
    <?php  
    }
    ?>
</div>
<div class="event-attributes">
    <div>
        <h2><?php   echo $eventTitle; ?> </h2>

        <div class="date-times">
            <?php  
            if (is_array($next_dates_array)) {
                foreach ($next_dates_array as $next_date) {
                    echo date(t('M dS '), strtotime($next_date->date));
                    if ($event_allday != 1) {
                        echo $th->formatTime($next_date->start) . ' - ' . $th->formatTime($next_date->end) . '<br/>';
                    } else {
                        echo ' - ' . t('All Day') . '<br/>';
                    }
                }
            }
            if ($event_recur == 'daily') {
                $date_array = $dtt->translate_from($c);
                echo '<small> ' . date(t('M dS '), strtotime($date_array[1]['date'])) . ' Thru ' . date(t('M dS '), strtotime($event_thru)) . ', ' . ucfirst($event_recur) . '</small>';
            }
            ?>
        </div>
        <div class="date-social">
            <?php  
            if ($tweets) {
                ?>
                <span class='st_twitter_hcount' displayText='Tweet'></span>
            <?php  
            }
            if ($fb_like) {
                ?>
                <span class='st_facebook_hcount' displayText='Facebook'></span>
            <?php  
            }
            if ($google) {
                ?>
                <span class='st_plusone_hcount' displayText='Plusone'></span>
                <?php  
            }
            ?>
            <script type="text/javascript">var switchTo5x = true;</script>
            <script type="text/javascript" src="//w.sharethis.com/button/buttons.js"></script>
            <script type="text/javascript">stLight.options({publisher: "<?php     echo $sharethis_key;?>"});</script>
            <?php  
            if (!empty($url)) {

                echo '<i><u><a href="' . $url . '">' . $url . '</a></u></i>';

            }
            ?>
        </div>
        <h5><?php   echo $event_local; ?>
            <?php   if ($event_local && $address) { ?> - <?php   }
            echo $address; ?>
        </h5>

        <div id="deswrap">
            <div id="description">
                <?php  
                if ($imageF) {
                    echo '<div class="pull-left thumb">';
                    echo '<img src="' . $image . '"/>';
                    echo '</div>';
                }
                ?>
                <?php  
                $content = $controller->getContent();
                print $content;
                ?>
            </div>
        </div>
        <div id="eventfoot">
            <?php   if ($contact_email != '') {
                echo '<a href="mailto:' . $contact_email . '">' . $contact_name . '</a>';
            } ?> <?php   if ($contact_email && $address) {
                echo '|| ';
            } ?> <?php   if ($address != '') { ?><a
                href="http://maps.google.com/maps?f=q&amp;hl=en&amp;&saddr=<?php   echo $address; ?>"
                target="_blank"> <?php   echo t('get directions') ?></a> <?php   } ?>
        </div>
        <div id="iCal">
            <p>
                <img src="<?php   echo $eventify->getiCalImgUrl(); ?>" width="25" alt="iCal feed"/>&nbsp;&nbsp;
                <a href="<?php  echo URL::to('/proevents/routes/ical')?>?ctID=<?php   echo $ctID; ?>&bID=<?php   echo $bID; ?>&ordering=<?php   echo $ordering; ?>&eID=<?php   echo $eID; ?>"
                   id="getFeed">
                    <?php   echo t('get iCal link'); ?></a>
            </p>
        </div>
        <div id="tags">
            <?php   echo t('Tags') ?>: <i>
                <?php  
                $ak_t = CollectionAttributeKey::getByHandle('event_tag');
                $tag_list = $c->getCollectionAttributeValue($ak_t);
                $akc = $ak_t->getController();

                if (method_exists($akc, 'getOptionUsageArray') && $tag_list) {
                    //$tags == $tag_list->getOptions();

                    foreach ($tag_list as $akct) {
                        $qs = $akc->field('atSelectOptionID') . '[]=' . $akct->getSelectAttributeOptionID();
                        echo '<a href="' . BASE_URL .'?cID='. $search_path . '&' . $qs . '">' . $akct->getSelectAttributeOptionValue(
                            ) . '</a>&nbsp;&nbsp;';

                    }

                }
                ?>
            </i>
        </div>
    </div>
</div>
<?php   if ($u->isLoggedIn() && $invites > 0) { ?>
    <script type="text/javascript">
        /*<![CDATA[*/
        $(document).ready(function () {
            $('#event_invite').dialog();
        });
        /*]]>*/
    </script>
<?php  
}
if ($u->isLoggedIn() && $user_events > 0) {
    ?>
    <script type="text/javascript">
        /*<![CDATA[*/
        $(document).ready(function () {
            $('.save_event').click(function (e) {
                e.preventDefault();
                var url = $(this).attr('href');
                $.ajax(url, {
                    error: function (r) {
                        console.log(r);
                    },
                    success: function (r) {
                        if ($('.save_event').attr('data-status') == 'remove') {
                            $('.save_event').html('<?php       echo t('Save Event'); ?>');
                            $('.save_event').attr('data-status', 'save');
                        } else {
                            $('.save_event').html('<?php       echo t('Remove Event'); ?>');
                            $('.save_event').attr('data-status', 'remove');
                        }
                    }
                });
            });
        });
        /*]]>*/
    </script>
<?php  
}
?>
	