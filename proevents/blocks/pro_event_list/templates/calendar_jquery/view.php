<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));

$holidays = 'http://www.google.com/calendar/feeds/usa__en%40holiday.calendar.google.com/public/basic';
$show_holidays = $settings['showHolidays'];
$tooltips = $settings['showTooltips'];
$tooltip_color = $settings['tooltipColor'];
$time_formatting = $settings['time_formatting'];
$timezone = $settings['tz_format'] ? $settings['tz_format'] : 'America/Chicago';
$themed = $settings['themed'] ? $settings['themed'] : 0;
$default_view = $settings['defaultView'];
$c = Page::getCurrentPage();
$ajax_url = URL::to('proevents/routes/calendar_jquery');
?>

<script type='text/javascript'>

    $(document).ready(function () {

        jQuery("#ctID").change(function () {
            filter_id = $(this).val();
            getCalendar(filter_id);
        });

        getCalendar = function (ctID) {
            if (!ctID) {
                ctID = '<?php     echo urlencode($ctID)?>';
            }
            $('#calendar').empty();
            var date = new Date();
            var d = date.getDate();
            var m = date.getMonth();
            var y = date.getFullYear();
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,basicWeek,agendaDay,agendaList'
                },
                //firstDay: 0, //0=Sunday
                monthNames: [
                    '<?php   echo t('January')?>', '<?php   echo t('February')?>', '<?php   echo t('March')?>', '<?php   echo t('April')?>', '<?php   echo t('May')?>', '<?php   echo t('June')?>', '<?php   echo t('July')?>', '<?php   echo t('August')?>', '<?php   echo t('September')?>', '<?php   echo t('October')?>', '<?php   echo t('November')?>', '<?php   echo t('December')?>'
                ],
                monthNamesShort: [
                    '<?php   echo t('Jan')?>', '<?php   echo t('Feb')?>', '<?php   echo t('Mar')?>', '<?php   echo t('Apr')?>', '<?php   echo t('May')?>', '<?php   echo t('Jun')?>', '<?php   echo t('Jul')?>', '<?php   echo t('Aug')?>', '<?php   echo t('Sep')?>', '<?php   echo t('Oct')?>', '<?php   echo t('Nov')?>', '<?php   echo t('Dec')?>'
                ],
                dayNames: [
                    '<?php   echo t('Sunday')?>', '<?php   echo t('Monday')?>', '<?php   echo t('Tuesday')?>', '<?php   echo t('Wednesday')?>', '<?php   echo t('Thursday')?>', '<?php   echo t('Friday')?>', '<?php   echo t('Saturday')?>'
                ],
                dayNamesShort: [
                    '<?php   echo t('Sun')?>', '<?php   echo t('Mon')?>', '<?php   echo t('Tues')?>', '<?php   echo t('Wed')?>', '<?php   echo t('Thur')?>', '<?php   echo t('Fri')?>', '<?php   echo t('Sat')?>'
                ],
                columnFormat: {
                    month: 'ddd',
                    week: 'ddd M/d',
                    day: 'dddd M/d'
                },
                allDayDefault: false,
                lazyFetching: false,
                eventSources: [
                    function (start, end, timezone, callback) {
                        $.ajax({
                            url: '<?php  echo $ajax_url?>?cID=<?php  echo $c->cID?>&bID=<?php  echo $bID?>',
                            dataType: 'json',
                            data: {
                                // our hypothetical feed requires UNIX timestamps
                                start: start.unix(),
                                end: end.unix()
                            },
                            success: function (doc) {
                                console.log(doc);
                                $.each(doc, function (key, obj) {
                                    callback(obj);
                                });
                            }
                        });
                    }
                ],
                timezoneParam: '<?php  echo $timezone?>',
                eventClick: function (event) {
                    if (event.url) {
                        window.open(event.url);
                        return false;
                    }
                },
                <?php     if($tooltips){ ?>
                eventRender: function (event, element) {
                    if (event.description) {
                        element.qtip({
                            content: event.description,
                            position: {
                                target: 'mouse',
                                adjust: {
                                    mouse: false  // Can be omitted (e.g. default behaviour)
                                }
                            },
                            style: { classes: 'qtip-<?php     echo $tooltip_color;?>' }
                        });
                    }
                },
                <?php     } ?>
                loading: function (bool) {
                    $('#loading').toggle(bool);
                },
            });
        }
        getCalendar();
    });
</script>
<br/>
<?php  
if ($showfilters > 0) {
    Loader::packageElement('category_filter', 'proevents', array('c' => $c, 'ctID' => $ctID));
}
?>
<div id="loading" style="display:none"><?php   echo t('loading') ?>...</div>
<div id="calendar"></div>
<?php  

if ($showfeed == 1) {
    ?>
    <div class="iCal">
        <p><img src="<?php   echo $ical_img_url; ?>" width="25" alt="iCal feed"/>&nbsp;&nbsp;
            <a href="<?php  echo URL::to('/proevents/routes/ical')?>?ctID=<?php   echo $ctID; ?>&bID=<?php   echo $bID; ?>&ordering=<?php   echo $ordering; ?>"
               id="getFeed">
                <?php   echo t('get iCal link'); ?></a></p>
        <link href="<?php  echo URL::to('/proevents/routes/ical')?>" rel="alternate" type="application/rss+xml"
              title="<?php   echo t('RSS'); ?>"/>
    </div>

<?php  
}
?>

