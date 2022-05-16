<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));

use \Concrete\Core\Page\Template;
$tmplt = Template::getByHandle('right_sidebar');

echo '<h3>'.t('Pre Install Checker').'</h3>';

if($tmplt && $tmplt->getPageTemplateID()){
    echo '<div class="ccm-ui" id="ccm-dashboard-result-message">';
    echo '    <div class="alert alert-success alert-dismissible">';
    echo '<img src="'.ASSETS_URL_IMAGES.'/icons/success.png" width="16" height="16"/> &nbsp; <strong> '.t('Excellent! Your theme has a right_sidebar!').'</strong>';
    echo '<br/><br/><p>'.t('Everything looks good! Go ahead and install ProEvents!').'</p>';
    echo '   </div>';
    echo '</div>';
}else{
    echo '<div class="ccm-ui" id="ccm-dashboard-result-message">';
    echo '    <div class="alert alert-danger alert-dismissible">';
    echo '<img src="'.ASSETS_URL_IMAGES.'/icons/error.png" width="16" height="16"/> &nbsp; <strong> '.t('No right_sidebar page template found').'</strong>';
    echo '<br/><br/><p>'.t('Danger Will Robinson!!! Your theme must have a right_sidebar page template to install!!!').'</p>';
    echo '<br/><p>'.t('Please <b><u>cancel</u></b> and register a right_sidebar page template to your theme before attempting to install ProEvents!').'</p>';
    echo '   </div>';
    echo '</div>';
}
?>
