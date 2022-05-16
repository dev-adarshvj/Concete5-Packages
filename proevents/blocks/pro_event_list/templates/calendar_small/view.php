<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));

$c = Page::getCurrentPage();

$ajax_url = URL::to('proevents/routes/calendar_small').'?title=' . $title;

//here we set up our drop down month select
$year = date('Y');
$month = date('m');
$day = date('d');
?>
<style>
    #event_cal {
        border-color: <?php     echo $settings['bordercolor'];?>;
    }

    #event_cal TD {
        border-color: <?php     echo $settings['bordercolor'];?>;
        background-color: <?php     echo $settings['cellcolor'];?>;
    }

    #event_cal TD:hover {
        background-color: <?php     echo $settings['cellhover'];?>;
    }

    #event_cal #cal_blank {
        background-color: <?php     echo $settings['blankdate'];?>;
    }

    #event_cal #current {
        background-color: <?php     echo $settings['currentdate'];?>;
    }

    #event_cal #allday, #allday a {
        background-color: <?php     echo $settings['alldaycolor'];?>;
    }

    a.tooltip span {
        border-style: solid;
        border-color: <?php     echo $settings['popupborder'];?>;
        border-width: 1px;
        color: <?php     echo $settings['popuptext'];?>;
        display: none;
        position: absolute;
        top: -110px;
        left: 10px;
        width: 155px;
        padding: 5px;
        z-index: 100;
        background: <?php     echo $settings['popupbg'];?>;
        -moz-border-radius: 5px; /* this works only in camino/firefox */
        -webkit-border-radius: 5px; /* this is just for Safari */
        -moz-box-shadow: 5px 5px 7px #666;
        -webkit-box-shadow: 5px 5px 7px #666;
        box-shadow: 5px 5px 7px #666;
    }

    a:hover.tooltip span {
        display: block;
    }
</style>
<script type="text/javascript">
    event_dialog = function () {
        jQuery.fn.dialog.open({
            width: 630,
            height: 450,
            modal: false,
            href: '<?php     echo BASE_URL.DIR_REL?>/index.php/tools/packages/proevents/add_event/',
            title: 'Add New Event',
            onClose: function () {
                $(window.location).attr('href', '<?php     echo $this->action('view')?>');
            }
        });
    }

    next_month = function () {
        var year = $("#setyear").val();
        var month = $("#setmo").val();

        if (month == '12') {
            ni = '01';
            year = (year * 1) + 1;
            $("#setyear").val(year);
        } else {
            var ni = (month * 1) + 01;
            ni = ni += '';

            if (ni.length < 2) {
                var ni = '0' + ni;
            }

        }

        var month = $("#setmo").val(ni);

        var ajax_url = '<?php     echo $ajax_url?>';
        var args = {
            bID: '<?php   echo $bID?>',
            cID: '<?php   echo $c->cID?>',
            ctID: '<?php   echo $ctID?>',
            sctID: '<?php   echo $sctID?>',
            year: year,
            month: ni,
            dateset: true
        }
        $.get(ajax_url, args, function (html) {
            //alert(html);
            $('#ajax_cal').html('');
            $('#ajax_cal').html(html);
        });
    }

    prev_month = function () {
        var year = $("#setyear option:selected").val();
        var month = $("#setmo option:selected").val();
        if (month == '01') {
            ni = '12';
            year = (year * 1) - 1;
            $("#setyear").val(year);
        } else {
            var ni = (month * 1) - 1;
            ni = ni += '';
            if (ni.length < 2) {
                var ni = '0' + ni;
            }
        }
        var month = $("#setmo").val(ni);
        //alert($ni);
        var ajax_url = '<?php     echo $ajax_url?>';
        var args = {
            bID: '<?php   echo $bID?>',
            cID: '<?php   echo $c->cID?>',
            ctID: '<?php   echo $ctID?>',
            sctID: '<?php   echo $sctID?>',
            year: year,
            month: ni,
            dateset: true
        }
        $.get(ajax_url, args, function (html) {
            //alert(html);
            $('#ajax_cal').html('');
            $('#ajax_cal').html(html);
        });
    }


    $(document).ready(function () {
        var year = $("#setyear option:selected").val();
        var month = $("#setmo option:selected").val();
        var ajax_url = '<?php     echo $ajax_url?>';
        var args = {
            bID: '<?php   echo $bID?>',
            cID: '<?php   echo $c->cID?>',
            ctID: '<?php   echo $ctID?>',
            sctID: '<?php   echo $sctID?>',
            year: year,
            month: month
        }
        $.get(ajax_url, args, function (html) {
            //alert(html);
            $('#ajax_cal').html(html);
        });
    });

    loadMyDialogDo = function (i) {

        var el = document.createElement('div')
        el.id = "myNewElementmyDialogContent" + i;
        el.innerHTML = $('.myDialogContent' + i).html();
        el.style.display = "none"
        $('.myDialogContent' + i).parent().append(el);
        jQuery.fn.dialog.open({
            title: 'View Events',
            element: '#myNewElementmyDialogContent' + i,
            width: 220,
            modal: false,
            height: 140,
        });
    }
</script>

<input type="hidden" value="0" id="cur_date"/>
<form action="<?php   echo $link; ?>" method="GET">

    <select name="setyear" id="setyear" style="display: none;">
        <option value="<?php   echo $year - 2 ?>"><?php   echo $year - 2 ?></option>
        <option value="<?php   echo $year - 1 ?>"><?php   echo $year - 1 ?></option>
        <option value="<?php   echo $year ?>" selected><?php   echo $year ?></option>
        <option value="<?php   echo $year + 1 ?>"><?php   echo $year + 1 ?></option>
        <option value="<?php   echo $year + 2 ?>"><?php   echo $year + 2 ?></option>
    </select>
    <select name="setmo" id="setmo" style="display: none;">
        <option value="01" <?php   if ($month == '01') {
            echo 'selected';
        } ?>><?php   echo t('Jan'); ?></option>
        <option value="02" <?php   if ($month == '02') {
            echo 'selected';
        } ?>><?php   echo t('Feb'); ?></option>
        <option value="03" <?php   if ($month == '03') {
            echo 'selected';
        } ?>><?php   echo t('Mar'); ?></option>
        <option value="04" <?php   if ($month == '04') {
            echo 'selected';
        } ?>><?php   echo t('Apr'); ?></option>
        <option value="05" <?php   if ($month == '05') {
            echo 'selected';
        } ?>><?php   echo t('May'); ?></option>
        <option value="06" <?php   if ($month == '06') {
            echo 'selected';
        } ?>><?php   echo t('Jun'); ?></option>
        <option value="07" <?php   if ($month == '07') {
            echo 'selected';
        } ?>><?php   echo t('Jul'); ?></option>
        <option value="08" <?php   if ($month == '08') {
            echo 'selected';
        } ?>><?php   echo t('Aug'); ?></option>
        <option value="09" <?php   if ($month == '09') {
            echo 'selected';
        } ?>><?php   echo t('Sep'); ?></option>
        <option value="10" <?php   if ($month == '10') {
            echo 'selected';
        } ?>><?php   echo t('Oct'); ?></option>
        <option value="11" <?php   if ($month == '11') {
            echo 'selected';
        } ?>><?php   echo t('Nov'); ?></option>
        <option value="12" <?php   if ($month == '12') {
            echo 'selected';
        } ?>><?php   echo t('Dec'); ?></option>
    </select>
    <input type="hidden" name="dateset" value="1">
</form>

<div id="ajax_cal"></div>