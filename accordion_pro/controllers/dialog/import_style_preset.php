<?php

/**
 * @project:   Accordion Pro add-on for concrete5
 *
 * @author     Fabian Bitter
 * @copyright  (C) 2017 Bitter Webentwicklung (www.bitter-webentwicklung.de)
 * @version    1.0.0.5
 */

namespace Concrete\Package\AccordionPro\Controller\Dialog;

defined('C5_EXECUTE') or die('Access denied');

use Concrete\Controller\Backend\UserInterface\Page as BackendPageController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Concrete\Package\AccordionPro\Src\StylePresets;

class ImportStylePreset extends BackendPageController
{
    protected $viewPath = '/dialogs/import_style_preset';
    
    public function on_start()
    {
        // Do nothing
    }
    
    public function canAccess()
    {
        return true;
    }
    
    public function import()
    {
        $success = false;
        
        if (isset($_FILES["file"]) && isset($_FILES["file"]["tmp_name"])) {
            $templateFile = $_FILES["file"]["tmp_name"];
            
            if (file_exists($templateFile)) {
                $success = StylePresets::getInstance()->importStylePreset($templateFile);
            }
        }

        return new JsonResponse(array(
            "success" => $success
        ));
    }
    
    public function view()
    {
        $this->requireAsset("dropzone");
    }
}
