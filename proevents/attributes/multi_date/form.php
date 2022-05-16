<?php   
defined('C5_EXECUTE') or die(_("Access Denied."));

$AJAX_url = URL::to('proevents/tools/excludedate');
$AJAX_url_multi = URL::to('proevents/tools/multidate');
?>
<style type="text/css">
    #dates_wrap .fa-trash{color: red!important;}
    #date-qty-message{color: red;}
</style>
<?php   
if ($type == 'date_time_time') {
    $ele = Loader::helper('form/date_time');
    $instance = rand(1, 2000000);
    $i = 1;
    //var_dump(DATE_APP_GENERIC_MDYT);
    ?>
    <a href="javascript:;" onClick="addDateTimeTime_<?php    echo $instance ?>(<?php    echo $instance ?>);" class="btn btn-success"><i class="fa fa-plus"></i> <?php   echo t('Add Event Date')?></a> &nbsp;&nbsp; <i id="date-qty-message">(<?php   echo t('You must have at least one date')?>)</i>
    <br/><br/>
    <div id="dates_wrap" class="dates_wrap_<?php    echo $instance ?> form-inline">
        <?php   
        //var_dump($values);
        //exit;
        echo Loader::helper('form')->hidden('akID[' . $akval . '][' . $i . '][reset]', 0);
        if ($values) {
            $dates = explode(':^:', $values);
            foreach ($dates as $date) {
                $i++;
                print $dtt->datetimetime('akID[' . $akval . '][' . $i . '][date]', $date, false, true, $instance);
            }
        }
        ?>
        <input type="hidden" value="<?php    echo $i ?>" id="dateCount" class="dcount_<?php     echo $instance?>"/>
    </div>
    <script type="text/javascript">
        
        initDateMessage_<?php echo $instance?> = function() {
            var numi = $('.dates_wrap_<?php  echo $instance?> #dateCount').val();
            if( numi > 1 ){
                $('#date-qty-message').hide();
            }else{
                $('#date-qty-message').show();
            }
        }
        
        checkDateMessage_<?php echo $instance?> = function() {
            var numi = $('.dates_wrap_<?php  echo $instance?> #dateCount').val();
            if(numi > 1){
                var num = (numi * 1) - 1;
                $('.dates_wrap_<?php  echo $instance?> #dateCount').val(num);
            }else{
                num = numi;
            }
            
            if( num > 1 ){
                $('#date-qty-message').hide();
            }else{
                $('#date-qty-message').show();
            }
        }
        
        addDateTimeTime_<?php  echo $instance?> = function (t) {
            
            var numi = $('.dates_wrap_' + t + ' #dateCount').val();
            var num = (numi * 1) + 1;
            
            if(num > 1){
                $('#date-qty-message').hide();
            }else{
                $('#date-qty-message').show();
            }
            
            
            //alert(num)
            $('.dates_wrap_' + t + ' #dateCount').val(num);
            var divIdName = "date" + num;

            $.ajax({
                url: '<?php   echo $AJAX_url_multi?>',
                data: {
                    instance: '<?php  echo $instance?>',
                    num: num,
                    akID: <?php   echo $akval?>
                },
                success: function (response) {
                    $('.dates_wrap_' + t).append(response);
                    $('.ccm-input-date').datepicker();
                }
            });
        }
        
        initDateMessage_<?php echo $instance?>();
    </script>
<?php   
} else {
    $ele = Loader::helper('form/date_time');
    $instance = rand(1, 2000000);
    ?>
    <style type="text/css">
    .excludeDate .ccm-input-date{display: inline-block!important;}
    </style>
    <a href="javascript:;" onClick="addDate_<?php    echo $instance ?>(<?php    echo $instance ?>);">[+] Add Date</a>
    <div id="dates_werap" class="date_wrap_<?php    echo $instance ?>">
        <?php   
        $i = 0;
        if ($values) {
            $dates = explode(':^:', $values);
            //var_dump($dates);
            foreach ($dates as $date) {
                if ($type == 'date_exclude') {
                    if (date('Y-m-d', strtotime($date)) >= date('Y-m-d')) {
                        $i++;
                        print '<div class="excludeDate"><a href="javascript:;" onClick="$(this).parent().remove();"><i class="fa fa-trash"></i></a> '.$dtt->date('akID[' . $akval . '][' . $i . '][value]', date('Y-m-d', strtotime($date))).'</div>';
                    }
                } else {
                    $i++;
                    print '<div class="excludeDate"><a href="javascript:;" onClick="$(this).parent().remove();"><i class="fa fa-trash"></i></a> '.$dtt->date('akID[' . $akval . '][' . $i . '][value]', date('Y-m-d', strtotime($date))).'</div>';
                }
            }
        }
        ?>
        <input type="hidden" value="<?php    echo $i ?>" id="dateCount"/>
    </div>
    <script type="text/javascript">
        addDate_<?php     echo $instance?> = function (t) {
            var numi = $('.date_wrap_' + t + ' #dateCount').val();
            var num = (numi * 1) + 1;
            //alert(num)
            $('.date_wrap_' + t + ' #dateCount').val(num);
            var divIdName = "date" + num;

            $.ajax({
                url: '<?php   echo $AJAX_url?>',
                data: {
                    num: num,
                    akID: <?php   echo $akval?>
                },
                success: function (response) {
                    $('.date_wrap_' + t).append(response);
                    $('.ccm-input-date').datepicker();
                }
            });
        }
    </script>
<?php   
}
?>
