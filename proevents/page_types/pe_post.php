<?php  
defined('C5_EXECUTE') or die("Access Denied.");
$eventify = Loader::helper('eventify', 'proevents');
global $c;
extract($eventify->getEventVars($c));
?>
<div id="pe_sidebar">
    <?php  
    $as = new Area('Sidebar');
    $as->display($c);
    ?>
</div>
<div id="pe_body">
    <?php  
    $a = new Area('Main');
    $a->display($c);
    ?>
</div>