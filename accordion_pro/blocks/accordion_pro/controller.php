<?php

/**
 * @project:   Accordion Pro add-on for concrete5
 *
 * @author     Fabian Bitter
 * @copyright  (C) 2017 Bitter Webentwicklung (www.bitter-webentwicklung.de)
 * @version    1.0.0.5
 */

namespace Concrete\Package\AccordionPro\Block\AccordionPro;

defined("C5_EXECUTE") or die("Access Denied.");

use Concrete\Package\AccordionPro\Src\AccordionItems;
use Concrete\Package\AccordionPro\Src\StylePresets;
use Concrete\Core\Block\BlockController;
use Core;

class Controller extends BlockController
{
    public $helpers = array(
        'form',
    );
    
    public $btFieldsRequired = array(
        'stylePresetId',
        'animationDuration',
        'collapse',
        'semanticTag'
    );
    
    protected $btExportFileColumns = array();
    protected $btDefaultSet = 'basic';
    protected $btTable = 'btAccordionPro';
    protected $btInterfaceWidth = 400;
    protected $btInterfaceHeight = 500;
    protected $btCacheBlockRecord = true;
    protected $btCacheBlockOutput = false;
    protected $btCacheBlockOutputOnPost = true;
    protected $btCacheBlockOutputForRegisteredUsers = true;
    
    public function getBlockTypeDescription()
    {
        return t("Create and add unlimited accordion elements to your website.");
    }

    public function getBlockTypeName()
    {
        return t("Accordion Pro");
    }

    public function view()
    {
        $this->requireAsset('css', 'style-presets');
        $this->requireAsset("css", "fontawesome");
        $this->requireAsset("javascript", "jquery");
        
        $items = AccordionItems::getInstance()->getItems($this->bID);
        $stylePreset = StylePresets::getInstance()->getStylePresetById($this->stylePresetId);
        
        $this->set("items", $items);
        $this->set("stylePreset", $stylePreset);
    }

    public function add()
    {
        $this->requireAsset('core/file-manager');
        $this->requireAsset('core/lightbox');
        $this->requireAsset('redactor');
        $this->requireAsset('jquery/ui');
        $this->requireAsset('css', 'fontawesome');
        $this->requireAsset("javascript", "mustache.js");
        
        $this->set("items", array());
        $this->set("stylePresets", StylePresets::getInstance()->getList());
    }
    
    public function edit()
    {
        $this->requireAsset('core/file-manager');
        $this->requireAsset('core/lightbox');
        $this->requireAsset('redactor');
        $this->requireAsset('jquery/ui');
        $this->requireAsset('css', 'fontawesome');
        $this->requireAsset("javascript", "mustache.js");
        
        $items = AccordionItems::getInstance()->getItemsAsArray($this->bID);
        
        $this->set("items", $items);
        $this->set("stylePresets", StylePresets::getInstance()->getList());
    }

    public function getSearchableContent()
    {
        $content = "";
        
        $items = AccordionItems::getInstance()->getItems($this->bID);
        
        if (is_array($items)) {
            foreach ($items as $item) {
                $content .= sprintf(
                    "%s%s %s",
                    ($content != "" ? " " : ""),
                    $item->getTitle(),
                    strip_tags($item->getParagraph())
                );
            }
        }
        
        return $content;
    }

    public function delete()
    {
        AccordionItems::getInstance()->removeItems($this->bID);
    }

    public function save($args)
    {
        parent::save($args);
        
        AccordionItems::getInstance()->setItems($this->bID, $args["items"]);
    }

    public function duplicate($newBID)
    {
        parent::duplicate($newBID);
        
        AccordionItems::getInstance()->duplicateItems($this->bID, $newBID);
    }
    
    public function validate($args)
    {
        $e = Core::make('helper/validation/error');

        if (!$args['animationDuration'] || !is_numeric($args['animationDuration']) || intval($args['animationDuration']) < 0) {
            $e->add(t('You must specify a valid animation duration.'));
        }
        
        if (isset($args["items"]) && is_array($args["items"])) {
            $missingTitle = false;
            
            foreach ($args["items"] as $item) {
                if (strlen($item["title"]) === 0) {
                    $missingTitle = true;
                    
                    break;
                }
            }
            
            if ($missingTitle) {
                $e->add(t('You must specify a valid title for each item.'));
            }
        }
        
        return $e;
    }
}
