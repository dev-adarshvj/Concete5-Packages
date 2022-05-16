<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));
?>
<style type="text/css">
    a:hover {
        text-decoration: none;
    }

    /*BG color is a must for IE6*/
    a.eventtooltip span {
        display: none;
        padding: 2px 3px;
        margin-left: 8px;
        margin-top: -20px;
    }

    a.eventtooltip:hover span {
        display: inline;
        position: absolute;
        background: #ffffff;
        border: 1px solid #cccccc;
        color: #6c6c6c;
    }

    th {
        text-align: left;
    }

    .align_top {
        vertical-align: top;
    }

    .ccm-results-list tr td {
        border-bottom-color: #dfdfdf;
        border-bottom-width: 1px;
        border-bottom-style: solid;
    }

    a.eventtooltip, a.eventtooltip span {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    td.action_events a:first-child {
        margin-left: 8px !important;
    }
    div#ccm-dashboard-content header{padding: 1px 80px 14px 95px;}

    .category_color_swatch{ width: 20px; height: 20px; float: right; margin-right: 22px;}
</style>
<?php   echo Loader::helper('concrete/dashboard')->getDashboardPaneHeaderWrapper(t('View/Search Events'),false,false,false); ?>
<?php  
if ($remove_name) {
    ?>
    <div class="ccm-ui" id="ccm-dashboard-result-message">
        <div class="alert alert-danger alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span
                    class="sr-only">Close</span></button>
            <p><strong><?php   echo t('Holy guacamole! This is a warning!'); ?></strong></p><br/>

            <p><?php   echo t('Are you sure you want to delete <b>') . t($remove_name) . '</b>?'; ?></p>

            <p><?php   echo t('This action may not be undone!'); ?></p>
            <br/>

            <div class="alert-actions">
                <a class="btn btn-danger small"
                   href="<?php   echo BASE_URL . DIR_REL; ?>/index.php/dashboard/proevents/event_list/delete/<?php   echo $remove_cid; ?>/<?php   echo $remove_name; ?>/"><?php   echo t(
                        'Yes Remove This'
                    ); ?></a>
            </div>
        </div>
    </div>
<?php  
}
?>
<div class="ccm-dashboard-content-full">

<!--
		<ul class="breadcrumb">
		  <li class="active">List <span class="divider">|</span></li>
		  <li><a href="/index.php/dashboard/proevents/add_event/">Add/Edit</a> <span class="divider">|</span></li>
		  <li><a href="/index.php/dashboard/proevents/preview/monthly/">Preview</a> <span class="divider">|</span></li>
		  <li><a href="/index.php/dashboard/proevents/exclude_dates/">Exclude Dates</a> <span class="divider">|</span></li>
		  <li><a href="/index.php/dashboard/proevents/settings/">Settings</a></li>
		</ul>
-->

<form method="get" action="<?php   echo $this->action('view') ?>">
    <?php  
    $sections[0] = '** All';
    asort($sections);
    ?>
    <table class="table">
        <tr>
            <th><strong><?php   echo $form->label('cParentID', t('Calendar')) ?></strong></th>
            <th><strong><?php   echo t('by Name') ?></strong></th>
            <th><strong><?php   echo t('by Category') ?></strong></th>
            <th><strong><?php   echo t('by Tag') ?></strong></th>
            <th></th>
        </tr>
        <tr>
            <td><?php   echo $form->select('cParentID', $sections, $cParentID) ?></td>
            <td><?php   echo $form->text('like', $like) ?></td>
            <td>
                <select name="cat" style="width: 110px!important;" class="form-control">
                    <option value=''>--</option>
                    <?php  
                    foreach ($cat_values as $cat) {
                        if ($_GET['cat'] == $cat['value']) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = null;
                        }
                        echo '<option ' . $selected . '>' . $cat['value'] . '</option>';
                    }
                    ?>
                </select>
            </td>
            <td>
                <select name="tag" style="width: 110px!important;" class="form-control">
                    <option value=''>--</option>
                    <?php  
                    foreach ($tag_values as $tag) {
                        if ($_GET['tag'] == $tag['value']) {
                            $selected = 'selected="selected"';
                        } else {
                            $selected = null;
                        }
                        echo '<option ' . $selected . '>' . $tag['value'] . '</option>';
                    }
                    ?>
                </select>
            </td>
            <td>
                <?php   echo $form->submit('submit', t('Search')) ?>
            </td>
        </tr>
    </table>

</form>
<br/>
<?php  
$nh = Loader::helper('navigation');
$fm = Loader::helper('form');
if (($eventList->get()) > 0) {
    //$eventList->displaySummary();
    ?>

    <table border="0" class="ccm-search-results-table" cellspacing="0" cellpadding="0">
        <thead>
        <tr>
            <th>&nbsp;</th>
            <th class=""><a href=""><?php   echo t('Name') ?></a></th>
            <th class=""><a href=""><?php   echo t('Dates') ?></a></th>
            <th class=""><a href=""><?php   echo t('Times') ?></a></th>
            <th><?php   echo t('Recurring') ?></th>
            <th><?php   echo t('Event Category') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php  
        foreach ($eventResults as $cobj) {

            if (is_object($cobj)) {

                Loader::model('attribute/categories/collection');
                $event_start = $cobj->getCollectionDatePublic('Y-m-d');
                $akve = CollectionAttributeKey::getByHandle('event_thru');
                $event_thru = $cobj->getCollectionAttributeValue($akve);
                $dates_array = Loader::helper('form/datetimetime')->translate_from($cobj);
                $akdp = CollectionAttributeKey::getByHandle('event_recur');
                $event_recur = $cobj->getCollectionAttributeValue($akdp);
                $akad = CollectionAttributeKey::getByHandle('event_allday');
                $event_allday = $cobj->getCollectionAttributeValue($akad);
                $event_section_id = $cobj->getCollectionParentID();
                $sec_page = Page::getByID($event_section_id);
                $event_section = $sec_page->getCollectionName();
                $akct = CollectionAttributeKey::getByHandle('event_category');
                $event_category = $cobj->getCollectionAttributeValue($akct);
                $color = $cobj->getAttribute('category_color');
                $pkt = Loader::helper('concrete/urls');
                $pkg = Package::getByHandle('proevents');
                $spokenFormat = $pkg->getConfig()->get('formatting.datespoken', false);
                $th = Loader::helper('form/time');
            }
            ?>
            <tr>
                <td width="115px" class="align_top action_events">
                    <a href="<?php   echo $this->url('/dashboard/proevents/add_event','edit',$cobj->getCollectionID()) ?>" class="eventtooltip icon edit"><i class="fa fa-edit"></i><span><?php   echo t('Edit this Event') ?></span></a> &nbsp;
                    <a href="<?php   echo $this->url('/dashboard/proevents/event_list','duplicate',$cobj->getCollectionID()) ?>" class="eventtooltip icon copy"><i class="fa fa-copy"></i><span><?php   echo t('Duplicate this Event') ?></span></a> &nbsp;
                    <a href="<?php   echo $this->url('/dashboard/proevents/event_list','delete_check',$cobj->getCollectionID(),$cobj->getCollectionName()) ?>" class="eventtooltip icon delete"><i class="fa fa-trash-o"></i><span><?php   echo t('Remove this Event') ?></span></a>
                </td>
                <td class="align_top">
                    <a href="<?php   echo $nh->getLinkToCollection($cobj) ?>"><?php   echo $cobj->getCollectionName() ?></a>
                </td>
                <td class="align_top">
                    <?php  
                    if (is_array($dates_array)) {
                        foreach ($dates_array as $var) {
                            echo date($spokenFormat, strtotime($var['date']));
                            echo '<br/>';
                        }
                    } else {
                        echo $event_start;
                    }
                    ?>
                </td>
                <td class="align_top">
                    <?php  
                    if ($event_allday == '1') {
                        echo t('All Day');
                    } else {
                        if ($dates_array[1]['date'] != '') {
                            foreach ($dates_array as $var) {
                                echo $th->formatTime($var['start']);
                                echo ' - ';
                                echo $th->formatTime($var['end']);
                                echo '<br/>';
                            }
                        } else {
                            echo $th->formatTime($start_time);
                            echo ' - ';
                            echo $th->formatTime($end_time);
                        }
                    }
                    ?>
                </td>
                <td class="align_top">
                    <?php  
                    if ($event_recur != '') {
                        echo $event_recur;
                        echo '<br/>';
                        echo t('thru ') . date('M d', strtotime($event_thru));
                    } else {
                        echo t('none');
                    }
                    ?>
                </td>
                <td class="align_top">
                    <?php  
                    $category = $cobj->getAttribute('event_category');

                    $akct = CollectionAttributeKey::getByHandle('event_category');
                    $tcvalue = $cobj->getAttributeValueObject($akct);
                    $color = $akct->getAttributeType()->getController()->getColorValue($category);
                    if ($color) {
                        echo '<div class="category_color_swatch" style="background-color: #' . $color . ';
                        "></div>';
                    }
                    echo $category;
                    ?>
                </td>
            </tr>
        <?php   } ?>
        </tbody>
    </table>
    <br/>
    <?php  
    //$eventList->displayPaging();
} else {
    print t('No event entries found.');
}
?>
</div>
<div class="ccm-pane-footer">

</div>
