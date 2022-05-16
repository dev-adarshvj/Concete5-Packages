<?php

/**
 * @project:   Accordion Pro add-on for concrete5
 *
 * @author     Fabian Bitter
 * @copyright  (C) 2017 Bitter Webentwicklung (www.bitter-webentwicklung.de)
 * @version    1.0.0.5
 */

defined('C5_EXECUTE') or die("Access Denied.");

View::element('/dashboard/Help', null, 'accordion_pro');
?>

<div class="ccm-ui">
    
    <form method="post" action="#" onsubmit="return false;" class="edit-style-preset-form">
        <div class="errorContainer">

        </div>

        <?php echo Core::make("helper/form")->hidden("presetId", $stylePreset->getPresetId()); ?>
        <?php echo Core::make("helper/form")->hidden("isSystemPreset", $stylePreset->getIsSystemPreset() ? 1 : 0); ?>
        
        <div class="form-horizontal">
            <?php foreach ($stylePreset->getSettingsArray() as $settingsGroup): ?>
                <legend>
                    <?php echo $settingsGroup["groupName"] ;?>
                </legend>

                <?php foreach ($settingsGroup["items"] as $settingsField): ?>
                    <div class="from-group form-row">
                        <?php
                            echo Core::make("helper/form")->label($settingsField["name"], $settingsField["label"]);

                            $attributes = array("class" => "form-control");

                            if ($settingsField["disabled"]) {
                                $attributes["disabled"] = "disabled";
                            }

                            switch ($settingsField["type"]) {
                                case "number":
                                    echo Core::make("helper/form")->number($settingsField["name"], $settingsField["value"], $attributes);
                                    break;

                                case "text":
                                    echo Core::make("helper/form")->text($settingsField["name"], $settingsField["value"], $attributes);
                                    break;

                                case "textarea":
                                    echo Core::make("helper/form")->textarea($settingsField["name"], $settingsField["value"], $attributes);
                                    break;

                                case "select":
                                    echo Core::make("helper/form")->select($settingsField["name"], $settingsField["options"], $settingsField["value"], $attributes);
                                    break;

                                case "color":
                                    $colorAttributes = array(
                                        'className' => 'ccm-widget-colorpicker',
                                        'showInitial' => true,
                                        'showInput' => true,
                                        'cancelText' => t('Cancel'),
                                        'chooseText' => t('Choose'),
                                        'preferredFormat' => 'hex',
                                        'clearText' => t('Clear Color Selection')
                                    );

                                    echo Core::make("helper/form/color")->output($settingsField["name"], $settingsField["value"], $colorAttributes);
                                    break;
                            }
                        ?>
                        
                        <?php if (isset($settingsField["helptext"]) && !empty($settingsField["helptext"])): ?>
                            <p class="help-block">
                                <?php echo $settingsField["helptext"]; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
        
        <style type="text/css">
            div.ui-dialog .ui-dialog-buttonpane {
                overflow: visible !important;
            }
            
            .edit-style-preset-form .form-row {
                clear: both;
                margin-bottom: 10px;
            }
            
            .edit-style-preset-form .ccm-widget-colorpicker {
                float: right;
            }
        </style>

        <script>
            var savePreset = function($form) {
                $.ajax({
                    type: "POST",
                    data: $form.serialize(),
                    url: '<?php echo $this->url("/dashboard/accordion_pro/dialogs/edit_style_preset", $stylePreset->getPresetId()); ?>',
                    success: function(data) {
                        if (data.success) {
                            $.fn.dialog.closeTop();
                            accordionPro.backend.refreshPresetList(<?php echo $stylePreset->getPresetId(); ?>);
                        } else {
                            var $errorBox = $form.find(".errorContainer");

                            $errorBox.html("");
                            
                            for(var i in data.errors) {
                                var errorMessage = data.errors[i];

                                $errorBox.append(
                                    $('<div></div>').html(errorMessage).addClass("alert").addClass("alert-danger")
                                );
                            }
                            
                            $errorBox.parent().parent().parent().scrollTop(0);
                        }
                    }
                });

                return false;
            };

            var removePreset = function() {
                if (confirm('<?php echo t("Are you sure?"); ?>')) {
                    $.ajax({
                        type: "GET",
                        url: '<?php echo $this->url("/dashboard/accordion_pro/dialogs/edit_style_preset/remove", $stylePreset->getPresetId()); ?>',
                        success: function() {
                            $.fn.dialog.closeTop();

                            accordionPro.backend.refreshPresetList(0);
                        }
                    });
                }
            };
            
            var closeDialog = function() {
                $.fn.dialog.closeTop();
            };
        </script>
        
        <div class="dialog-buttons">
            <div class="pull-left" style="margin-top: 6px;">
                <?php if ($stylePreset->getIsSystemPreset()): ?>
                    <a href="javscript:void(0);" class="btn btn-danger pull-left disabled" data-dialog-action="cancel" disabled="disabled">
                        <i class="fa fa-trash-o" aria-hidden="true"></i> <?php echo t('Remove')?>
                    </a>
                <?php else: ?>
                    <a href="javascript:void(0);" class="btn btn-danger pull-left" data-dialog-action="cancel" onclick="return removePreset();">
                        <i class="fa fa-trash-o" aria-hidden="true"></i> <?php echo t('Remove')?>
                    </a>
                <?php endif; ?>
            </div>
            
            <div class="pull-right">
                <button type="button" class="btn btn-primary pull-right" onclick="return savePreset($(this).parent().parent().parent().parent().find('form'));">
                    <i class="fa fa-floppy-o" aria-hidden="true"></i> <?php echo t('Save')?>
                </button>
                
                <div class="dropup pull-right">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">
                        
                        <?php echo t("More"); ?>
                        
                        <span class="caret"></span>
                    </button>
                    
                    <ul class="dropdown-menu">
                        <li>
                            <a href="<?php echo $this->url("/dashboard/accordion_pro/dialogs/edit_style_preset/export", $stylePreset->getPresetId()); ?>" target="_blank">
                                <i class="fa fa-download" aria-hidden="true"></i> <?php echo t('Export')?>
                            </a>
                        </li>
                        
                        <li>
                            <a href="javascript:void(0);" onclick="return closeDialog();">
                                <i class="fa fa-times" aria-hidden="true"></i> <?php echo t('Close')?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </form>
</div>