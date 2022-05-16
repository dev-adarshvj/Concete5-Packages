<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
?>
<table class="entry-form" cellspacing="1" cellpadding="0">
    <tr>
        <td class="subheader"><?php   echo t('Ask User For') ?></td>
    </tr>
    <tr>
        <?php  
        $akDateDisplayModeOptions = array(
            'date_time_time' => t('Date plus start and end Time'),
            //'date_time' => t('Both Date and Time'),
            'date' => t('Date Only'),
            'date_exclude' => t('Exclude Dates')
        );
        ?>
        <td><?php   echo $form->select('akDateDisplayMode', $akDateDisplayModeOptions, $akDateDisplayMode) ?></td>
    </tr>
</table>