<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
$fm = Loader::helper('form');
?>

<?php   echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(
    t('ProEvents Settings'),
    false,
    false,
    false
); ?>

<div class="ccm-pane-body ccm-ui">

    <form method="post" id="settings" action="<?php   echo $this->action('save_settings'); ?>">
        <h4><?php   echo t('jQuery Calendar Options') ?></h4>
        <br/>

        <div class="form-group">
            <div class="checkbox">
                <label class="checkbox-inline">
                    <input type="checkbox" name="showHolidays" value="true" <?php   if ($showHolidays == true) {
                        echo 'checked';
                    } ?>> <?php   echo t('Show Holidays'); ?>
                </label>
                <label class="checkbox-inline">
                    <input type="checkbox" name="showTooltips" value="true" <?php   if ($showTooltips == true) {
                        echo 'checked';
                    } ?>> <?php   echo t('Show ToolTips'); ?>
                </label>
            </div>
            <br/>

            <div class="select">
                <label>
                    <?php   echo t('ToolTip Color '); ?>
                </label>
                <select name="tooltipColor" class="form-control">
                    <option value="light" <?php   if ($tooltipColor == 'light') {
                        echo 'selected';
                    } ?>><?php   echo t('light'); ?></option>
                    <option value="dark" <?php   if ($tooltipColor == 'dark') {
                        echo 'selected';
                    } ?>><?php   echo t('dark'); ?></option>
                    <option value="red" <?php   if ($tooltipColor == 'red') {
                        echo 'selected';
                    } ?>><?php   echo t('red'); ?></option>
                    <option value="blue" <?php   if ($tooltipColor == 'blue') {
                        echo 'selected';
                    } ?>><?php   echo t('blue'); ?></option>
                    <option value="green" <?php   if ($tooltipColor == 'green') {
                        echo 'selected';
                    } ?>><?php   echo t('green'); ?></option>
                    <option value="cream" <?php   if ($tooltipColor == 'cream') {
                        echo 'selected';
                    } ?>><?php   echo t('cream'); ?></option>
                </select>
            </div>
        </div>


        <br/>


        <h4><?php   echo t('Additional Settings'); ?></h4>
        <br/>

        <div class="form-group">
            <div class="input">
                <label>
                    <?php   echo t('iCal/jQuery Timezone') ?>
                </label>
                <?php   echo $fm->text('tz_format', $tz_format, array('size' => '12')); ?>
                <p>Please refer to the "TZ" column in the list found at: <a
                        href="http://en.wikipedia.org/wiki/List_of_tz_database_time_zones">http://en.wikipedia.org/wiki/List_of_tz_database_time_zones</a>
                </p>
            </div>
            <br/>

            <div class="input">
                <label>
                    <?php   echo t('Time Format') ?>
                </label>
                <select name="timeformat" class="form-control">
                    <option value="12" <?php   if ($timeformat == '12') {
                        echo 'selected';
                    } ?>><?php   echo t('12hr'); ?></option>
                    <option value="24" <?php   if ($timeformat == '24') {
                        echo 'selected';
                    } ?>><?php   echo t('24hr'); ?></option>
                </select>
            </div>
            <br/>

            <div class="input">
                <label>
                    <?php   echo t('Date Format') ?>
                </label>
                <input type="text" name="datefault" id="Form-field-Settings-pe_date_full" value="<?php  echo $datefault?>" placeholder="Y-m-d" class="form-control" autocomplete="off" maxlength="255">
                <p>Please refer to the php date formatting outlined here: <a href="http://php.net/manual/en/function.date.php">http://php.net/manual/en/function.date.php</a>
                </p>
            </div>
            <br/>

            <div class="input">
                <label>
                    <?php   echo t('Date Spoken Format') ?>
                </label>
                <input type="text" name="datespoken" id="Form-field-Settings-pe_date_full" value="<?php  echo $datespoken?>" placeholder="l M jS, Y" class="form-control" autocomplete="off" maxlength="255">
                <p>Please refer to the php date formatting outlined here: <a href="http://php.net/manual/en/function.date.php">http://php.net/manual/en/function.date.php</a>
                </p>
            </div>
            <br/>

            <div class="input">
                <label>
                    <?php   echo t('DatePicker Format') ?>
                </label>
                <input type="text" name="datepicker" id="Form-field-Settings-pe_date_full" value="<?php  echo $datepicker?>" placeholder="yy-mm-dd" class="form-control" autocomplete="off" maxlength="255">
                <p>Please refer to the jquery date formatting outlined here: <a href="http://api.jqueryui.com/datepicker/#utility-formatDate">http://api.jqueryui.com/datepicker/#utility-formatDate</a>
                </p>
            </div>
            <br/>

            <div class="input">
                <label>
                    <?php   echo t('Search Path') ?>
                </label>
                <?php  
                $pgp = Loader::helper('form/page_selector');
                echo $pgp->selectPage('search_path', $search_path);
                ?>
            </div>
            <br/>

            <div class="input">
                <label>
                    <?php   echo t('Show') ?>
                </label>
            </div>
            <div class="checkbox">
                <label class="checkbox-inline">
                    <input name="tweets" type="checkbox" value="1" <?php   if ($tweets == 1) {
                        echo ' checked';
                    } ?> /> <?php   echo t('Twitter') ?>
                </label>
                <label class="checkbox-inline">
                    <input name="google" type="checkbox" value="1" <?php   if ($google == 1) {
                        echo ' checked';
                    } ?> /> <?php   echo t('Google +1') ?>
                </label>
                <label class="checkbox-inline">
                    <input name="fb_like" type="checkbox" value="1" <?php   if ($fb_like == 1) {
                        echo ' checked';
                    } ?> /> <?php   echo t('Facebook Like') ?>
                </label>
                <label class="checkbox-inline">
                    <input name="invites" type="checkbox" value="1" <?php   if ($invites == 1) {
                        echo ' checked';
                    } ?> /> <?php   echo t('Allow Invites') ?>
                </label>
                <label class="checkbox-inline">
                    <input name="user_events" type="checkbox" value="1" <?php   if ($user_events == 1) {
                        echo ' checked';
                    } ?> /> <?php   echo t('Allow Users to Save Events') ?>
                </label>
            </div>
            <br/>

            <div class="input">
                <label>
                    <?php   echo t('ShareThis Key') ?>
                </label>
                <?php   echo $fm->text('sharethis_key', $sharethis_key, array('size' => '12')); ?>
                </br>
                <?php   echo t('required for social sharing') ?></i>
            </div>
        </div>

</div>
<div class="ccm-pane-footer">
    <button type="submit" class="btn btn-primary generated_date_submit" style="float: right;"><?php  echo  t('Save Settings') ?></button>
    </form>
</div>