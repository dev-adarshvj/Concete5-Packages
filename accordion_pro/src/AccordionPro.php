<?php

/**
 * @project:   Accordion Pro add-on for concrete5
 *
 * @author     Fabian Bitter
 * @copyright  (C) 2017 Bitter Webentwicklung (www.bitter-webentwicklung.de)
 * @version    1.0.0.5
 */

namespace Concrete\Package\AccordionPro\Src;

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Package\AccordionPro\Src\StylePresets;
use Concrete\Package\AccordionPro\Src\Entity\AccordionItem;
use View;

class AccordionPro
{
    private static $instance = null;

    /**
     * @return AccordionPro
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }
    
    /**
     *
     * @param array $options
     * @param array $items
     *
     * @return boolean
     */
    public function outputAccordion($options = array(), $items = array())
    {
        // prepare options
        $templatePreset = "Accordion minimal";
        $animationDuration = "300";
        $semanticTag = "h2";
        
        if (is_array($options)) {
            if (isset($options["templatePreset"])) {
                $templatePreset = $options["templatePreset"];
            }

            if (isset($options["animationDuration"])) {
                $animationDuration = $options["animationDuration"];
            }

            if (isset($options["semanticTag"])) {
                $semanticTag = $options["semanticTag"];
            }
        }
        
        // prepare items
        $fakeEntities = array();
        
        if (is_array($items)) {
            foreach ($items as $item) {
                $fakeEntity = new AccordionItem;

                if (isset($item["title"])) {
                    $fakeEntity->setTitle($item["title"]);
                }

                if (isset($item["paragraph"])) {
                    $fakeEntity->setParagraph($item["paragraph"]);
                }

                if (isset($item["isOpen"])) {
                    $fakeEntity->setIsOpen(intval($item["isOpen"]) === 1);
                } else {
                    $fakeEntity->setIsOpen(false);
                }

                array_push($fakeEntities, $fakeEntity);
            }
        }
        
        $stylePreset = StylePresets::getInstance()->getStylePresetByName($templatePreset);
        
        if (is_object($stylePreset)) {
            // require assets
            View::getInstance()->requireAsset('css', 'style-presets');
            View::getInstance()->requireAsset("css", "fontawesome");
            View::getInstance()->requireAsset("javascript", "jquery");

            // append html to view
            View::getInstance()->inc("../../../packages/accordion_pro/blocks/accordion_pro/view.php", array(
                "templatePreset" => $templatePreset,
                "stylePresetId" => $stylePreset->getPresetId(),
                "animationDuration" => $animationDuration,
                "semanticTag" => $semanticTag,
                "stylePreset" => $stylePreset,
                "items" => $fakeEntities
            ));
            
            // include view.js
            $viewJsUrl = str_replace("/index.php", "", View::url("/")) . "/packages/accordion_pro/blocks/accordion_pro/view.js";
            View::getInstance()->addFooterItem('<script type="text/javascript" src="' . $viewJsUrl . '"></script>');
        } else {
            return false;
        }
    }
}
