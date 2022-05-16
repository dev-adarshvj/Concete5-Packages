<?php

/**
 * @project:   Accordion Pro add-on for concrete5
 *
 * @author     Fabian Bitter
 * @copyright  (C) 2017 Bitter Webentwicklung (www.bitter-webentwicklung.de)
 * @version    1.0.0.5
 */

defined('C5_EXECUTE') or die('Access denied');

View::element('/dashboard/Help', null, 'accordion_pro');

$fp = FilePermissions::getGlobal();
$tp = new TaskPermission();

$tabs = array(
    array('items', t('Items'), true),
    array('options', t('Options'))
);

echo Core::make('helper/concrete/ui')->tabs($tabs);

?>

<script id="itemTemplate" type="x-tmpl-mustache">
    <div id="item-{{id}}" class="item closed">
        <div class="form-group">
            <?php echo $form->label("title", t("Title")); ?>
            <?php echo $form->text("items[{{id}}][title]", "{{title}}"); ?>

            <p class="help-block">
                <?php echo t("Click on the textbox to show all fields of this item."); ?>
            </p>
        </div>

        <div class="extendedContent">
            <div class="form-group">
                <?php echo $form->label("paragraph", t("Paragraph")); ?>
                
                <?php 
                    $html = $form->textarea("%id%", "{{paragraph}}");
                    $html = str_replace("id=\"%id%\"", "id=\"redactor-{{id}}\"", $html);
                    $html = str_replace("name=\"%id%\"", "name=\"items[{{id}}][paragraph]\"", $html);

                    echo $html;
                ?>
            </div>

            <div class="form-group">
                <?php echo $form->label("isOpen", t("State")); ?>
                <?php echo $form->select("items[{{id}}][isOpen]", array(0 => t("Closed"), 1 => t("Open")), array("onchange" => "return accordionPro.backend.checkOpenState({{id}});")); ?>
            </div>

            <a href="javascript:void(0);" class="btn btn-danger" onclick="return accordionPro.backend.removeItem({{id}});">
                <i class="fa fa-trash-o" aria-hidden="true"></i>  <?php echo t("Remove Item"); ?>
            </a>

            <a href="javascript:void(0);" class="btn btn-default" onclick="return accordionPro.backend.collapseItems();">
                <?php echo t("Collapse"); ?>
            </a>
        </div>
    </div>
</script>

<style type="text/css">                
    .item {
        position: relative;
        min-height: 20px;
        padding: 10px;
        margin-bottom: 10px;
        background-color: #f5f5f5;
        border: 1px solid #e3e3e3;
        border-radius: 4px;
        -moz-box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
        -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
        box-shadow: inset 0 1px 1px rgba(0,0,0,0.05);
    }

    .item:before {
        font: normal normal normal 14px/1 FontAwesome;
        position: absolute;
        top: 6px;
        right: 6px;
        font-size: 14px !important;
        content: "\f047";
        font-weight: lighter;
        color: #666 !important;
        cursor: move;
    }
    
    .item .help-block {
        display: none;
    }
    
    .item.closed .help-block {
        display: block;
    }
    
    .item.closed .extendedContent {
        display: none;
    }
    
    .redactor-editor {
        min-height: 150px;
    }
  
    .dropdown-menu {
          z-index: 10000;
    }
</style>

<div id="ccm-tab-content-items" class="ccm-tab-content">
    <div class="alert alert-info hidden" id="noItems">
        <?php echo t("You don't have any items. Please click <a href=\"javascript:void(0);\" onclick=\"return accordionPro.backend.appendEmptyItem()\">here</a> to add one."); ?>
    </div>
    
    <div id="itemsContainer">
        
    </div>

    <div>
        <a href="javascript:void(0);" id="addItem" class="btn btn-success">
            <i class="fa fa-plus" aria-hidden="true"></i> <?php echo t("Add Item"); ?>
        </a>
        
        <a href="javascript:void(0);" id="addDummyItem" class="btn btn-default">
            <i class="fa fa-plus" aria-hidden="true"></i> <?php echo t("Add Dummy Item"); ?>
        </a>
    </div>
</div>

<div id="ccm-tab-content-options" class="ccm-tab-content">
    <div class="form-group">
        <?php echo $form->label("stylePresetId", t("Style Preset")); ?>
        <?php echo $form->select("stylePresetId", $stylePresets, $stylePresetId); ?>
    </div>
    
    <a href="javascript:void(0);" id="editPreset" class="btn btn-default">
        <i class="fa fa-pencil" aria-hidden="true"></i> <?php echo t("Edit preset"); ?>
    </a>
    
    <a href="javascript:void(0);" id="importPresets" class="btn btn-default">
        <i class="fa fa-upload" aria-hidden="true"></i> <?php echo t('Import presets')?>
    </a>
    
    <a href="javascript:void(0);" id="addPreset" class="btn btn-success">
        <i class="fa fa-plus" aria-hidden="true"></i> <?php echo t("Add preset"); ?>
    </a>
    
    <hr>
	
	 <div class="form-group">
        <?php echo $form->label("faqTitle", t("Faq Title")); ?>
        <?php echo $form->text("faqtitle",$faqtitle); ?>
    </div>
    
    <div class="form-group">
        <?php echo $form->label("collapse", t("Allow multiple open items")); ?>
        <?php echo $form->select("collapse", array(0 => t("No"), 1 => t("Yes")), $colapse); ?>
    </div>
    
    <div class="form-group">
        <?php echo $form->label("scrollToActiveItem", t("Scroll to active item")); ?>
        <?php echo $form->select("scrollToActiveItem", array(0 => t("No"), 1 => t("Yes")), $scrollToActiveItem); ?>
    </div>
    
    <div class="form-group">
        <?php echo $form->label("semanticTag", t("Semantic tag for titles")); ?>
        <?php echo $form->select("semanticTag", array("h2" => "h2", "h3" => "h3", "h4" => "h4", "h5" => "h5", "h6" => "h6"), $semanticTag); ?>
    </div>
    
    <div class="form-group">
        <?php echo $form->label("animationDuration", t("Animation duration")); ?>

        <div class="input-group">
            <?php echo $form->number("animationDuration", is_null($animationDuration) ? 300 : $animationDuration); ?>
            
            <span class="input-group-addon" id="basic-addon2">
                <?php echo t("ms"); ?>
            </span>
        </div>
        
        <p class="help-block">
            <?php echo t("The total duration of the slide animation."); ?>
        </p>
    </div>
</div>

<script type="text/javascript">
    var CCM_EDITOR_SECURITY_TOKEN = "<?php  echo Core::make('helper/validation/token')->generate('editor')?>";
    
    $(document).ready(function() {
        accordionPro.backend.init(<?php echo json_encode(array(
            "items" => $items,
            "removePrompt" => t("Are you sure?"),
            "addDialogTitle" => t("Add style preset"),
            "addDialogUrl" => $this->url("/dashboard/accordion_pro/dialogs/edit_style_preset/add"),
            "editDialogTitle" => t("Edit style preset"),
            "editDialogUrl" => $this->url("/dashboard/accordion_pro/dialogs/edit_style_preset"),
            "getStylePresetsUrl" => $this->url("/dashboard/accordion_pro/dialogs/edit_style_preset/all"),
            "importDialogTitle" => t("Import style preset"),
            "importDialogUrl" => $this->url("/dashboard/accordion_pro/dialogs/import_style_preset"),
            "redactorFilemanager" => $fp->canAccessFileManager(),
            "redactorSitemap" => true
        ), true);?>);
    });
</script>