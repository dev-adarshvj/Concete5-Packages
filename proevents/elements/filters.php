<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
?>
<div id="event_filters">
    <select id="date_span" class="filters">
        <option value=""><?php   echo t('All') ?></option>
        <option value="Day"><?php   echo t('Today') ?></option>
        <option value="Week"><?php   echo t('This Week') ?></option>
        <option value="FollowingWeek"><?php   echo t('Next Week') ?></option>
        <option value="Month"><?php   echo t('By Month') ?></option>
        <option value="FollowingMonth"><?php   echo t('Next Month') ?></option>
    </select>
    <select id="date_jump" class="filters" style="display: none;">
        <option value=""><?php   echo t('All') ?></option>
        <?php  
        for ($d = 0; $d < 12; $d++) {
            $m = date('Y-m-d', strtotime('+' . $d . ' month'));
            echo '<option value="' . $m . '">' . date('M, Y', strtotime($m)) . '</option>';
        }
        ?>
    </select>
    <select id="date_cat" class="filters">
        <option value="All Categories"><?php   echo t('All Categories') ?></option>
        <?php  

        $options = Loader::helper('eventify')->getEventCats();
        foreach ($options as $option) {
            echo '<option value="' . $option['value'] . '">' . $option['value'] . ' </option>';
        }
        ?>
    </select>
</div>

<script type="text/javascript">
    /*<![CDATA[*/
    $(document).ready(function(){
        var url = '<?php    echo $AJAX_url?>';
        $('.filters').change(function () {
            $('.ajax_loader').show();
            var span = $('#date_span option:selected').val();
            if (span == 'Month') {
                $('#date_jump').show();
            } else {
                $('#date_jump').val('');
                $('#date_jump').hide();
            }
            var date = $('#date_jump option:selected').val();
            var cat = $('#date_cat option:selected').val();
            var args = {
                ccID: <?php    echo $c->getCollectionID();?>,
                bID: <?php  echo $bID?>,
                type: span,
                category: cat,
                date: date,
                joinDays: true
            };
            $.get(url, args, function (data) {
                $('.ajax_loader').hide();
                $('#event_results').html(data);
            });
        });

    });
    /*]]>*/
</script>