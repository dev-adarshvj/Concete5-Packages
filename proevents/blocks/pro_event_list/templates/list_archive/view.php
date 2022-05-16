<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));

$c = Page::getCurrentPage();
?>
    <h1><?php   echo $rssTitle ?></h1>

    <div class="ccm-page-list">
        <?php  
        if (count($eArray) > 0) {

            foreach ($eArray as $date_string => $event) {

                extract($eventify->getEventListVars($event));

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
                            <div class="month">
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
                            <div class="title">
                                <?php  
                                echo '<a href="' . $url . '?eID=' . $eID . '">' . $title . '</a>';
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
                        <div class="description">
                            <?php  
                            if ($truncateChars) {
                                print  substr($content, 0, $truncateChars) . '.....';
                            } else {
                                print  $content;
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php  
                //#####################################################################################//
                //this is the end of the recommended content area.  please do not edit below this line //
                //#####################################################################################//

            }
            //is iCal feed option is sellected, show it
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

<?php   if ($showPagination): ?>
    <?php   echo $pagination; ?>
<?php   endif; ?>