<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));

extract($settings);
$c = Page::getCurrentPage();

///////////////////////////////////////////////////////
//This can be removed.  This is really here to explain
//to newer users of C5 that they can easily change
//how PE is displayed. One less support ticket!
///////////////////////////////////////////////////////

if ($c->isEditMode()) {
    echo '<i style="color: orange;max-width: 400px;display: block;">' . t(
            'Don\'t forget, you can quickly change this to Calendar view by clicking on this area, and selecting "Custom Template".<br/><br/>'
        ) . '</i>';
}

///////////////////////////////////////////////////////
///////////////////////////////////////////////////////
?>
<h1><?php   echo $rssTitle ?></h1>

<div class="ccm-page-list metro_list">
    <?php  
    if (count($eArray) > 0) {

        foreach ($eArray as $date_string => $event) {
            $i++;
            extract($eventify->getEventListVars($event));

            //###################################################################################//
            //here we lay out they way the page looks, html with all our vars fed to it	     //
            //this is the content that displays.  it is recommended not to edit anything beyond  //
            //the content parse.  Feel free to structure and re-arrange any element and adjust   //
            //CSS as desired.							 	     //
            //###################################################################################//

            ?>

            <?php  
            $date_month = date('M', strtotime($date));
            if ($cur_month != $date_month) {
                echo '</div>';
                echo '<div class="ccm-page-list metro_list">';
                echo '<div class="month">';
                echo $months[$date_month] . ', ' . date('Y', strtotime($date));
                echo '</div>';
            }
            $cur_month = $date_month;
            ?>

            <div class="smallcal <?php   echo $i ?>">

                <div style="background-color: #<?php   echo $color ?>;" class="category_color"></div>

                <div class="day">
                    <?php   echo date('d', strtotime($date)); ?>
                </div>

                <div class="infowrap">
                    <div class="titlehead">
                        <div class="title">
                            <?php  
                            echo '<h4>' . $title . '</h4>';
                            ?>
                        </div>
                        <div class="time">
                            <?php  
                            if (is_array($next_dates_array)) {
                                foreach ($next_dates_array as $next_date) {
                                    echo date(t('M dS '), strtotime($next_date->date));
                                    if ($allday != 1) {
                                        echo date(t('g:i a'), strtotime($next_date->start)) . ' - ' . date(
                                                t('g:i a'),
                                                strtotime($next_date->end)
                                            ) . '<br/>';
                                    } else {
                                        echo ' - ' . t('All Day') . '<br/>';
                                    }
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <div class="details_container" style="display: none;">
                        <div class="description">
                            <h5><?php   echo t('Event Details') ?></h5>
                            <?php  
                            if ($truncateChars) {
                                print  substr($content, 0, $truncateChars) . '.....';
                            } else {
                                print  $content;
                            }
                            ?>
                            <?php  
                            echo '<a href="' . $url;
                            if (!$grouped) {
                                echo '?eID=' . $eID;
                            }
                            echo '">' . t('Read More') . '</a>';
                            ?>
                        </div>
                        <div class="event_times">
                            <h5><?php   echo t('Event Times') ?></h5>
                            <?php  
                            if (is_array($next_dates_array)) {
                                foreach ($next_dates_array as $next_date) {
                                    echo date('M dS ', strtotime($next_date->date));
                                    if ($allday != 1) {
                                        echo date(t('g:i a'), strtotime($next_date->start)) . ' - ' . date(
                                                t('g:i a'),
                                                strtotime($next_date->end)
                                            ) . '<br/>';
                                    } else {
                                        echo ' - ' . t('All Day') . '<br/>';
                                    }
                                }
                            }
                            ?>
                        </div>
                        <?php   if ($address) { ?>
                            <div class="local">
                                <img
                                    src="http://maps.googleapis.com/maps/api/staticmap?center=&zoom=9&size=1100x300&maptype=roadmap&markers=color:blue%7Clabel:S%7C<?php   echo $address ?>&sensor=false"
                                    alt="image_map" class="location_map_image"/> <br/>
                                <h5><?php   echo $location; ?></h5>
                                <a href="http://maps.google.com/maps?f=q&amp;hl=en&amp;&saddr=<?php   echo $address; ?>"
                                   target="_blank"> <?php   echo t('get directions') ?></a>
                            </div>
                        <?php   } ?>
                        <?php  
                        if ($contact_email != '') {
                            ?>
                            <div class="contact">
                                <a href="mailto:<?php   echo $contact_email; ?>"><?php   echo t(
                                        'contact: '
                                    ); ?><?php   echo $contact_name ?></a>
                            </div>
                        <?php   } ?>
                    </div>
                </div>
            </div>
            <br style="clear: both;"/>
            <?php  
            //#####################################################################################//
            //this is the end of the recommended content area.  please do not edit below this line //
            //#####################################################################################//

        }
        //is rss feed option is sellected, show it
        if ($showfeed == 1) {
            ?>
            <div class="iCal">
                <p><img src="<?php   echo $rss_img_url; ?>" width="25" alt="iCal feed"/>&nbsp;&nbsp;
                    <a href="<?php  echo URL::to('/proevents/routes/rss')?>?bID=<?php   echo $bID; ?>&ordering=<?php   echo $ordering; ?>"
                       id="getFeed">
                        <?php   echo t('get RSS feed'); ?></a></p>
                <link href="<?php  echo URL::to('/proevents/routes/rss')?>?bID=<?php   echo $bID; ?>" rel="alternate"
                      type="application/rss+xml" title="<?php   echo t('RSS'); ?>"/>
            </div>
        <?php  
        }
    } else {
        echo '<p>' . $nonelistmsg . '</p>';
    }
    ?>
</div>
<br style="clear: both;"/>

<?php   if ($showPagination): ?>
    <?php   echo $pagination; ?>
<?php   endif; ?>

<script type="text/javascript">
    /*<![CDATA[*/
    $(document).ready(function () {
        $('.smallcal').hover(
            function () {
                $(this).addClass('event_cal_hover');
                $(this).find('.category_color').css({width: '4px'}).animate({width: '8px'});
            },
            function () {
                $(this).removeClass('event_cal_hover');
                $(this).find('.category_color').css({width: '8px'}).animate({width: '4px'});
            }
        );

        $('.smallcal').each(function () {
            $(this).bind('click', function () {
                if ($(this).find('.details_container').css('display') == 'none') {
                    $(this).find('.details_container').slideDown('slow');
                } else {
                    $(this).find('.details_container').slideUp('slow');
                }
            });
        });
    });
    /*]]>*/
</script>