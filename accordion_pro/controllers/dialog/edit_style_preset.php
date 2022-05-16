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
use Request;

class EditStylePreset extends BackendPageController
{
    protected $viewPath = '/dialogs/edit_style_preset';
    
    public function getStylePresets()
    {
        return new JsonResponse(array("items" => StylePresets::getInstance()->getList()));
    }
    
    public function on_start()
    {
        // Do nothing
    }
    
    public function canAccess()
    {
        return true;
    }
    
    public function add()
    {
        $stylePreset = StylePresets::getInstance()->addEmptyStylePreset();
        
        if (is_object($stylePreset)) {
            $this->redirect("/dashboard/accordion_pro/dialogs/edit_style_preset", $stylePreset->getPresetId());
        } else {
            $this->redirect("page_not_found");
        }
    }
    
    public function export($stylePresetId)
    {
        $stylePreset = StylePresets::getInstance()->getStylePresetById($stylePresetId);
        
        if (is_object($stylePreset)) {
            $stylePreset->export();
        } else {
            $this->redirect("page_not_found");
        }
    }
    
    public function remove($stylePresetId)
    {
        $stylePreset = StylePresets::getInstance()->getStylePresetById($stylePresetId);
        
        if (is_object($stylePreset)) {
            $stylePreset->remove();
            
            exit();
        } else {
            $this->redirect("page_not_found");
        }
    }
    
    public function view($stylePresetId)
    {
        $stylePreset = StylePresets::getInstance()->getStylePresetById($stylePresetId);
        
        if (is_object($stylePreset)) {
            $this->set("stylePreset", $stylePreset);

            if (Request::getInstance()->isPost()) {
                $errors = $stylePreset->apply($this->post());
                
                $errorList = array();
                
                if ($errors->has()) {
                    foreach ($errors->getList() as $errorMessage) {
                        if (is_object($errorMessage)) {
                            array_push($errorList, $errorMessage->getMessage());
                        } else {
                            array_push($errorList, $errorMessage);
                        }
                    }
                } else {
                    $stylePreset->save(true);
                }
                
                return new JsonResponse(
                    array(
                        "success" => count($errorList) === 0,
                        "errors" => $errorList
                    )
                );
            }
        } else {
            $this->redirect("page_not_found");
        }
    }
}
