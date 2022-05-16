<?php

/**
 * @project:   Accordion Pro add-on for concrete5
 *
 * @author     Fabian Bitter
 * @copyright  (C) 2017 Bitter Webentwicklung (www.bitter-webentwicklung.de)
 * @version    1.0.0.5
 */

namespace Concrete\Package\AccordionPro;

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Package\AccordionPro\Src\StylePresets;
use Package;
use Route;
use BlockType;
use AssetList;
use Config;
use Core;

class Controller extends Package
{
    protected $pkgHandle = 'accordion_pro';
    protected $pkgVersion = '1.0.0.6';
    protected $appVersionRequired = '5.7.0.4';
    
    public function getPackageDescription()
    {
        return t('Create and add unlimited accordion elements to your website.');
    }

    public function getPackageName()
    {
        return t('Accordion Pro');
    }
    
    public function on_start()
    {
        $this->initComponents();
        $this->registerAssets();
        $this->bindCoreClasses();
    }
    
    public function bindCoreClasses()
    {
        Core::bind('accordion', function () {
            return \Concrete\Package\AccordionPro\Src\AccordionPro::getInstance();
        });
    }
    
    private function registerAssets()
    {
        AssetList::getInstance()->register('javascript', 'mustache.js', "bower_components/mustache.js/mustache.js", array("version" => "2.3.0"), $this->pkgHandle);
        
        $fileTS = "";
        
        if (Config::get('concrete.cache.assets') === false) {
            $fileTS = filemtime(DIR_PACKAGES .  "/accordion_pro/css/style_presets.css");
        }
        
        AssetList::getInstance()->register('css', 'style-presets', "css/style_presets.css" . ($fileTS != "" ? "?ts=" . $fileTS : ""), array(), $this->pkgHandle);
    }
    
    public function initComponents($basicRequirementsOnly = false)
    {
        if ($basicRequirementsOnly === false) {
            $this->registerRoutes();
        }
    }
    private function registerRoutes()
    {
        // register custom routes
        Route::register("/dashboard/accordion_pro/dialogs/import_style_preset", "\Concrete\Package\AccordionPro\Controller\Dialog\ImportStylePreset::view");
        Route::register("/dashboard/accordion_pro/dialogs/import_style_preset/import", "\Concrete\Package\AccordionPro\Controller\Dialog\ImportStylePreset::import");
        Route::register("/dashboard/accordion_pro/dialogs/edit_style_preset/all", "\Concrete\Package\AccordionPro\Controller\Dialog\EditStylePreset::getStylePresets");
        Route::register("/dashboard/accordion_pro/dialogs/edit_style_preset/add", "\Concrete\Package\AccordionPro\Controller\Dialog\EditStylePreset::add");
        Route::register("/dashboard/accordion_pro/dialogs/edit_style_preset/remove/{stylePresetId}", "\Concrete\Package\AccordionPro\Controller\Dialog\EditStylePreset::remove");
        Route::register("/dashboard/accordion_pro/dialogs/edit_style_preset/export/{stylePresetId}", "\Concrete\Package\AccordionPro\Controller\Dialog\EditStylePreset::export");
        Route::register("/dashboard/accordion_pro/dialogs/edit_style_preset/{stylePresetId}", "\Concrete\Package\AccordionPro\Controller\Dialog\EditStylePreset::view");
    }

    public function addBlockTypeIfNotExists($blockTypeName)
    {
        $pkg = Package::getByHandle($this->pkgHandle);

        $blockType = BlockType::getByHandle($blockTypeName);

        if (is_object($blockType) === false) {
            $blockType = BlockType::installBlockType($blockTypeName, $pkg);
        }
    }

    public function installOrUpdateBlockTypes()
    {
        $this->addBlockTypeIfNotExists("accordion_pro");
    }
    
    private function installOrUpdate()
    {
        $this->installOrUpdateBlockTypes();
    }

    public function upgrade()
    {
        $this->initComponents(true);
        
        $this->installOrUpdate();

        parent::upgrade();
        
        // recompile the css
        StylePresets::getInstance()->compileCss();
    }

    public function install()
    {
        $this->initComponents(true);
        
        parent::install();
        
        $this->installOrUpdate();
        
        StylePresets::getInstance()->installSystemTemplates();
        
        // recompile the css
        StylePresets::getInstance()->compileCss();
    }

    public function uninstall()
    {
        $this->initComponents(true);
        
        StylePresets::getInstance()->uninstallSystemTemplates();
        
        parent::uninstall();
    }
}
