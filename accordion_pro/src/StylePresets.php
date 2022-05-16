<?php

/**
 * @project:   SUN TAXI
 *
 * @author     Fabian Bitter
 * @copyright  (C) 2016 Bitter Webentwicklung (www.bitter-webentwicklung.de)
 * @version    1.0.0.5
 */

namespace Concrete\Package\AccordionPro\Src;

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Package\AccordionPro\Src\Entity\StylePreset;
use Concrete\Package\AccordionPro\Src\Helper;
use Database;
use lessc;

class StylePresets
{
    private static $instance = null;
    private $em;

    /**
     * @return StylePresets
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }
    
    public function __construct()
    {
        $this->em = Database::connection()->getEntityManager();
    }
    
    /**
     *
     * @param StylePreset $stylePreset
     *
     * @return integer
     */
    public function addStylePreset($stylePreset)
    {
        $this->em->persist($stylePreset);
        $this->em->flush();
        
        return $stylePreset->getPresetId();
    }
    
    /**
     *
     * @return StylePreset
     */
    public function addEmptyStylePreset()
    {
        $stylePreset = new StylePreset;
        
        $stylePreset->applyDefaults();
        
        $this->addStylePreset($stylePreset);
                
        return $stylePreset;
    }
    
    /**
     *
     * @param integer $stylePresetId
     *
     * @return StylePreset
     */
    public function getStylePresetById($stylePresetId)
    {
        return $this->em->
            getRepository('Concrete\Package\AccordionPro\Src\Entity\StylePreset')->
            findOneBy(array('presetId' => $stylePresetId));
    }
    
    /**
     *
     * @param string $stylePresetName
     *
     * @return StylePreset
     */
    public function getStylePresetByName($stylePresetName)
    {
        return $this->em->
            getRepository('Concrete\Package\AccordionPro\Src\Entity\StylePreset')->
            findOneBy(array('presetName' => $stylePresetName));
    }
    
    /**
     *
     * @return Array
     */
    public function getAll()
    {
        return $this->em->
            getRepository('Concrete\Package\AccordionPro\Src\Entity\StylePreset')->
            findBy(array());
    }
    
    /**
     *
     * @return Array
     */
    public function getList()
    {
        $listItems = array();
        
        $entities = $this->getAll();
        
        if (is_array($entities)) {
            foreach ($entities as $entity) {
                $listItems[$entity->getPresetId()] = $entity->getPresetName();
            }
        }
        
        return $listItems;
    }
    
    public function uninstallSystemTemplates()
    {
        foreach ($this->getAll() as $entity) {
            if ($entity->getIsSystemPreset()) {
                $entity->remove();
            }
        }
    }
    
    public function installSystemTemplates()
    {
        // Style Preset 1 (Accordion minimal)
        $stylePreset1 = $this->addEmptyStylePreset();
        
        $stylePreset1->setIsSystemPreset(true);
        $stylePreset1->setPresetName(t("Accordion minimal"));
        $stylePreset1->setTitlePaddingTop("10px");
        $stylePreset1->setTitlePaddingRight("25px");
        $stylePreset1->setTitleTextFontWeight("bold");
        $stylePreset1->setTitleIconPadding("4px");
        $stylePreset1->setContentPaddingTop("17px");
        $stylePreset1->setContentPaddingBottom("13px");
        
        $stylePreset1->save();
        
        // Style Preset 2 (Accordion with background)
        $stylePreset2 = $this->addEmptyStylePreset();
        
        $stylePreset2->setIsSystemPreset(true);
        $stylePreset2->setPresetName(t("Accordion with background"));
        $stylePreset2->setTitlePaddingLeft("10px");
        $stylePreset2->setTitlePaddingTop("14px");
        $stylePreset2->setTitlePaddingBottom("9px");
        $stylePreset2->setTitleBackgroundColorNormal("#f7f7f7");
        $stylePreset2->setTitleBackgroundColorHover("#f7f7f7");
        $stylePreset2->setTitleBackgroundColorActive("#f7f7f7");
        $stylePreset2->setTitleIconBackgroundColorNormal("transparent");
        $stylePreset2->setTitleIconBackgroundColorHover("transparent");
        $stylePreset2->setTitleIconBackgroundColorActive("transparent");
        $stylePreset2->setTitleTextFontWeight("normal");
        $stylePreset2->setTitleIconPadding("0px");
        $stylePreset2->setTitleIconMarginRight("14px");
        $stylePreset2->setTitleMarginBottom("12px");
        
        $stylePreset2->setContentPaddingTop("5px");
        $stylePreset2->setContentPaddingBottom("13px");
        
        $stylePreset2->save();
        
        // Style Preset 3 (Accordion with dividers)
        $stylePreset3 = $this->addEmptyStylePreset();
        
        $stylePreset3->setIsSystemPreset(true);
        $stylePreset3->setPresetName(t("Accordion with dividers"));
        $stylePreset3->setTitlePaddingLeft("0px");
        $stylePreset3->setTitlePaddingTop("13px");
        $stylePreset3->setTitlePaddingBottom("9px");
        $stylePreset3->setTitleBorderBottomWidth("1px");
        $stylePreset3->setTitleBorderBottomColor("#e6e6e6");
        $stylePreset3->setTitleBorderBottomStyle("solid");
        
        // @todo: Border Bottom Type (solid)
        $stylePreset3->setTitleTextFontWeight("bold");
        $stylePreset3->setTitleIconPadding("4px");
        $stylePreset3->setContentPaddingTop("17px");
        $stylePreset3->setContentPaddingBottom("13px");
        
        $stylePreset3->save();
        
        $this->compileCss();
    }
    
    
    public function compileCss()
    {
        $lessCompiler = new lessc;
        
        $lessCompiler->setFormatter("compressed");
        
        $fileHelper = \Core::make('helper/file');

        $lessCode = $fileHelper->getContents(DIR_PACKAGES . "/accordion_pro/less/style_preset.less");
        
        $compiledCssCode = '';
        
        //$cssFileName = DIR_PACAKGES . "/accordion_pro/css/style_presets.css";
        
        foreach ($this->getAll() as $stylePreset) {
            $combinedLessCode = $stylePreset->getLessVariablesAsString();
            $combinedLessCode .= $lessCode;
            
            $compiledCssCode .= $lessCompiler->compile($combinedLessCode);
        }
        
        $fileHelper->clear(DIR_PACKAGES . "/accordion_pro/css/style_presets.css");
        $fileHelper->append(DIR_PACKAGES . "/accordion_pro/css/style_presets.css", $compiledCssCode);
    }
    
    /**
     * @return boolean
     */
    public function importStylePreset($stylePresetFile)
    {
        $fileHelper = \Core::make('helper/file');

        $rawData = $fileHelper->getContents($stylePresetFile);
        
        $xmlData = simplexml_load_string($rawData);

        $arrData = Helper::xml2array($xmlData);
        
        if (isset($arrData["StylePreset"])) {
            $presetData = $arrData["StylePreset"];
            
            $stylePreset = $this->addEmptyStylePreset();
            
            $errors = $stylePreset->apply($presetData);
            
            if ($errors->has()) {
                $stylePreset->remove();
            } else {
                $stylePreset->save(true);
                
                return true;
            }
        }
        
        return false;
    }
}
