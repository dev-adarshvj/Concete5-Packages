<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
$c = Page::getCurrentPage();

$ajax_url = URL::to('proevents/routes/calendar_dynamic').'?title=' . $title . '&sctID=' . $sctID . '&ctID=' . $ctID .
'&cID=' . $c->cID . '&bID=' . $bID . $state;
?>
<script type="text/javascript">
    $(document).ready(function () {
        $('.month_next').click(function () {
            var month_array = new Array();
            month_array[0] = "<?php   echo t('Jan')?>";
            month_array[1] = "<?php   echo t('Feb')?>";
            month_array[2] = "<?php   echo t('Mar')?>";
            month_array[3] = "<?php   echo t('Apr')?>";
            month_array[4] = "<?php   echo t('May')?>";
            month_array[5] = "<?php   echo t('Jun')?>";
            month_array[6] = "<?php   echo t('Jul')?>";
            month_array[7] = "<?php   echo t('Aug')?>";
            month_array[8] = "<?php   echo t('Sep')?>";
            month_array[9] = "<?php   echo t('Oct')?>";
            month_array[10] = "<?php   echo t('Nov')?>";
            month_array[11] = "<?php   echo t('Dec')?>";

            var year = $("#year").val();
            var month = $('#month_title').attr('alt');

            if (month == '12') {
                ni = '01';
                year = (year * 1) + 1;
                $("#year").val(year);
            } else {
                var ni = (month * 1) + 01;
                ni = ni += '';

                if (ni.length < 2) {
                    var ni = '0' + ni;
                }

            }

            $('#month_title').attr('alt', ni);
            $('#month_title').html(month_array[ni - 1].toUpperCase() + ' ' + year);

            var ajax_url = '<?php     echo $ajax_url?>&nab_days=true&month=' + ni + '&year=' + year;
            $.get(ajax_url, function (html) {
                //alert(html);
                $('#dynamic_body').html(html);
                $('.dyday').click(function () {
                    var day = $(this).html();
                    nab_dates(day);
                });
            });
        });

        $('.month_prev').click(function () {
            var month_array = new Array();
            month_array[0] = "<?php   echo t('Jan')?>";
            month_array[1] = "<?php   echo t('Feb')?>";
            month_array[2] = "<?php   echo t('Mar')?>";
            month_array[3] = "<?php   echo t('Apr')?>";
            month_array[4] = "<?php   echo t('May')?>";
            month_array[5] = "<?php   echo t('Jun')?>";
            month_array[6] = "<?php   echo t('Jul')?>";
            month_array[7] = "<?php   echo t('Aug')?>";
            month_array[8] = "<?php   echo t('Sep')?>";
            month_array[9] = "<?php   echo t('Oct')?>";
            month_array[10] = "<?php   echo t('Nov')?>";
            month_array[11] = "<?php   echo t('Dec')?>";

            var year = $("#year").val();
            var month = $('#month_title').attr('alt');

            if (month == '01') {
                ni = '12';
                year = (year * 1) - 1;
                $("#year").val(year);
            } else {
                var ni = (month * 1) - 1;
                ni = ni += '';
                if (ni.length < 2) {
                    var ni = '0' + ni;
                }
            }

            $('#month_title').attr('alt', ni);
            $('#month_title').html(month_array[ni - 1].toUpperCase() + ' ' + year);

            var ajax_url = '<?php     echo $ajax_url?>&nab_days=true&month=' + ni + '&year=' + year;
            $.get(ajax_url, function (html) {
                //alert(html);
                $('#dynamic_body').html(html);
                $('.dyday').click(function () {
                    var day = $(this).html();
                    nab_dates(day);
                });
            });
        });

        var ajax_url = '<?php     echo $ajax_url?>&nab_days=true';
        $.get(ajax_url, function (html) {
            //alert(html);
            $('#dynamic_body').html(html);
            $('.dyday').click(function () {
                var day = $(this).html();
                nab_dates(day);
            });
        });
    });

    function nab_dates(i) {
        var month = $('#month_title').attr('alt');
        var day = i;
        var year = $('#year').val();
        var ajax_url = '<?php     echo $ajax_url?>&nab_date=true&month=' + month + '&day=' + day + '&year=' + year;
        //alert(month + '-' + day  + '-' + year);
        $.get(ajax_url, function (html) {
            //alert(html);
            $('#dynamic_dayinfo').empty();
            $('#dynamic_dayinfo').html(html);
            nab_events(i);
        });
    }
    function nab_events(i) {
        var month = $('#month_title').attr('alt');
        var day = i;
        var year = $('#year').val();
        var ajax_url = '<?php     echo $ajax_url?>&nab_events=true&month=' + month + '&day=' + day + '&year=' + year;
        //alert(month + '-' + day  + '-' + year);
        $.get(ajax_url, function (html) {
            //alert(html);
            $('#dynamic_footer').empty();
            $('#dynamic_footer').html(html);
        });
    }
    function show_description(i) {
        if ($('.description_' + i).css('display') == 'none') {
            $('.description_' + i).slideDown('slow');
        } else {
            $('.description_' + i).slideUp('slow');
        }
    }
</script>
<div id="dynamic_contain">
    <div id="dynamic_header">
        <div class="month_prev month_nav"> &lt;&lt; </div>
        <div class="month_next month_nav"> &gt;&gt; </div>
        <div id="month_title" alt="<?php   echo date('m') ?>"><?php   echo strtoupper($months[date('M')]) ?><?php   echo date(
                ' Y'
            ) ?></div>
        <input type="hidden" name="year" id="year" value="<?php   echo date('Y') ?>"/>
    </div>
    <div id="dynamic_body">

    </div>
    <br style="clear: both;"/>

    <div id="dynamic_dayinfo">
        <div id="dayname">
            <?php  
            echo strtoupper(date('l'));
            ?>
        </div>
        <div id="day">
            <div id="month">
                <?php  
                echo strtoupper($months[date('M')]);
                ?>
            </div>
            <?php  
            echo date('d');
            ?>
        </div>
        <br style="clear: both"/>
    </div>
    <div id="dynamic_footer">
        <?php   echo t('no events for this day') ?>
    </div>
</div>