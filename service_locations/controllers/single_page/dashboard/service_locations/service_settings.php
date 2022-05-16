<?php
namespace Concrete\Package\ServiceLocations\Controller\SinglePage\Dashboard\ServiceLocations;
use \Concrete\Core\Attribute\Key\Category as AttributeKeyCategory;
use \Concrete\Core\Page\Controller\DashboardPageController;
use Loader;
use PageList;
use TaskPermission;
use Environment;
use Exception;
use \Concrete\Package\ServiceLocations\Src\LocationSettings;
use \Concrete\Core\Page\Type\PublishTarget\Type as PageTypePublishTargetType;
use Config;
use FileSet;
use Package;
use Page;
use File;
use PageTemplate;
use PageType;
use \Concrete\Core\Editor\LinkAbstractor;
defined("C5_EXECUTE") or die(_("Access Denied."));
class ServiceSettings extends DashboardPageController
{
    public function on_start()
    {
        $this->error = Loader::helper('validation/error');
        $this->set('pageTitle', 'Service Settings');
        $this->set('metaTitle', '');
    }
    
    public function view()
    {
		
		$new_pl = new PageList();
        $new_pl->filterByServiceLocationsSection(1);
		$this->set('parent_page', $new_pl->get());
        $ServiceSettings = new LocationSettings();
        $variable        = $ServiceSettings->getVariable();
        $this->set('variables', $variable);
        
        $category = AttributeKeyCategory::getByID(1);
        $sets     = $category->getAttributeSets();
        $setsarr  = array();
        foreach ($sets as $set) {
            $setsarr[$set->getAttributeSetID()] = $set->getAttributeSetName();
        }
        $this->set('attribute_sets', $setsarr);
        $ctArray       = PageTemplate::getList();
        $PageTemplates = array(
            '' => 'Select Page Template'
        );
        foreach ($ctArray as $ct) {
            $PageTemplates[$ct->getPageTemplateID()] = $ct->getPageTemplateName();
        }
        $this->set('PageTemplates', $PageTemplates);
		
        $ctArray   = PageType::getList();
        $PageTypes = array(
            '' => 'Select Page Type'
        );
		
        foreach ($ctArray as $ct) {
            $PageTypes[$ct->getPageTypeID()] = $ct->getPageTypeName();
        }
		
        $this->set('PageTypes', $PageTypes);
        $pkg         = Package::getByHandle("service_locations");
        $this->token = Loader::helper('validation/token');
		
        $this->set('attribute_set_id', $pkg->getConfig()->get('service.SERVICE_LOCATIONS_ATTRIBUTE_SET_ID'));
        $this->set('page_type_id', $pkg->getConfig()->get('service.SERVICE_LOCATIONS_COLLECTION_TYPE_ID'));
        $this->set('page_template_id', $pkg->getConfig()->get('service.SERVICE_LOCATIONS_PAGE_TEMPLATE_ID'));
        
        $this->set('defaultContent', $pkg->getConfig()->get('service.DEFAULT_CONTENT'));
        //$this->set('metaTitle',$pkg->getConfig()->get('service.META_TITLE'));
        $this->set('metaDescription', $pkg->getConfig()->get('service.META_DESCRIPTION'));
        //$this->set('pageTitle',$pkg->getConfig()->get('service.PAGE_TITLE'));
        $this->set('metaTags', $pkg->getConfig()->get('service.META_TAGS'));
        
        $this->set('fileSets', FileSet::getMySets());
        
        
    }
    
    
    public function save_settings()
    {
        Loader::model("attribute/categories/collection");
        //Loader::model('config');
        $this->token = Loader::helper('validation/token');
        if ($this->token->validate("save_settings")) {
            if ($this->isPost()) {
                
                
                
                //$co = new Config();
                $pkg = Package::getByHandle("service_locations");
                //$co->setPackageObject($pkg);
                if (isset($_POST['SERVICE_LOCATIONS_ATTRIBUTE_SET_ID'])) {
                    $pkg->getConfig()->save('service.SERVICE_LOCATIONS_ATTRIBUTE_SET_ID', $_POST['SERVICE_LOCATIONS_ATTRIBUTE_SET_ID']);
                    
                    $pt = PageTemplate::getByID($this->post('SERVICE_LOCATIONS_PAGE_TEMPLATE_ID'));
                    $ct = PageType::getByID($this->post('SERVICE_LOCATIONS_COLLECTION_TYPE_ID'));
                    //print_r($ct);
                    //print_r($pt);die;
                    
                    $defaultContent  = $this->post('defaultContent');
                    $metaTitle       = $this->post('metaTitle');
                    $metaDescription = $this->post('metaDescription');
                    $metaTags        = $this->post('metaTags');
                    $pageTitle       = $this->post('pageTitle');
                    $parent_page     = $this->post('internalLinkCID');
                    
                    // print_r($parent_page);
                    // die();
                    
                    $fileset_id = $this->post('fileset_choosed');
                    
                    
                    $parent_page_object = Page::getByID($parent_page);
                    
                    
                    
                    $pkg->getConfig()->save('service.DEFAULT_CONTENT', $defaultContent);
                    $pkg->getConfig()->save('service.META_TITLE', $metaTitle);
                    $pkg->getConfig()->save('service.META_DESCRIPTION', $metaDescription);
                    $pkg->getConfig()->save('service.META_TAGS', $metaTags);
                    $pkg->getConfig()->save('service.PAGE_TITLE', $pageTitle);
                    
                    if (empty($ct)) {
                        
                        $this->error->add(t('You must choose a Page Type.'));
                    }
                    if (empty($pt)) {
                        
                        $this->error->add(t('You must choose a Page Template.'));
                    }
                    
                    if ($this->isPost() && $this->post('internalLinkCID') <= 0) {
                        $this->error->add(t('You must choose a parent page for this Page entry.'));
                    }
                    
                    if ($this->post('internalLinkCID')) {
                        $parent = Page::getByID($this->post('internalLinkCID'));
                    }
                    
                    $id = $this->post('update');
                    
                    
                    if ($id) {
                        
                        $new_pl = new PageList();
                        $new_pl->filterByParentID($parent_page);
                        $new_pages = $new_pl->get();
                        
                        
                        
                        $variable = LocationSettings::getVariable();
                        
                        foreach ($variable as $availabl) {
                            $available_variable[] = $availabl['variable'];
                        }
                        if (!$this->error->has()) {
                            
                            $parent_page_object->setAttribute('service_meta_title', $metaTitle);
                            $parent_page_object->setAttribute('service_meta_description', $metaDescription);
                            $parent_page_object->setAttribute('service_meta_keywords', $metaTags);
                            $parent_page_object->setAttribute('additional_service_content', $defaultContent);
                            $parent_page_object->setAttribute('service_page_title', $pageTitle);
                            
                            foreach ($new_pages as $child) {
                                
                                
                                $variable_value = unserialize($child->getAttribute('page_variable_value'));
                                
                                
                                $metaTitle_modified       = $this->replace_content_on_update($metaTitle, $variable_value);
                                $metaDescription_modified = $this->replace_content_on_update($metaDescription, $variable_value);
                                $metaTags_modified        = $this->replace_content_on_update($metaTags, $variable_value);
                                $defaultContent_modified  = $this->replace_content_on_update($defaultContent, $variable_value);
                                $pageTitle_modified       = $this->replace_content_on_update($pageTitle, $variable_value);
                                
                                if (is_object($child)) {
                                    
                                    $parent = Page::getByID($this->post('cParentID'));
                                    $data   = array(
                                        'pTemplateID' => $this->post('SERVICE_LOCATIONS_PAGE_TEMPLATE_ID')
                                    );
                                    $child->update($data);
                                    $child->setAttribute('meta_title', $metaTitle_modified);
                                    $child->setAttribute('meta_description', $metaDescription_modified);
                                    $child->setAttribute('meta_keywords', $metaTags_modified);
                                    $child->setAttribute('service_content', $defaultContent_modified);
                                    $child->setAttribute('page_title', $pageTitle_modified);
                                    $child->setAttribute('page_variable_value', serialize($variable_value));
                                    if (!empty($fileset_id)) {
                                        $child->setAttribute('fileset_info', $fileset_id);
                                    }
                                    
                                }
                            }
                            
                        }
                        
                        
                    } else {
                        
                        
                        
                        
                        $details_given = false;
                        if ($this->post('fID')) {
                            /*THE SECTION FOR READING CSV FILE -Start*/
                            $details_given = true;
                            $fileID        = $this->post('fID');
                            $f             = File::getByID($fileID);
                            $file          = fopen($f->getURL(), "r");
                            $pages         = array();
                            
                            while ($row = fgetcsv($file)) {
                                $pages[] = $row;
                            }
                            $available_var = LocationSettings::getVariableName();
                            $header_var    = $pages['0'];
                            foreach ($available_var as $available) {
                                $available_variable[] = $available['variable'];
                            }
                            
                            $header_not_found = false;
                            foreach ($available_variable as $variable) {
                                if (!(in_array($variable, $header_var))) {
                                    $header_not_found = true;
                                }
                            }
                            if ($header_not_found == true) {
                                $this->error->add(t('CSV  header missing OR Variable mismatch in the header. Please check the csv file and try again'));
                            }
                            
                            foreach ($available_variable as $variable) {
                                $key                      = array_search($variable, $header_var);
                                $replace_value[$variable] = $key;
                            }
                            /*END*/
                        } elseif ($this->post('manual_id')) {
                            
                            /*SECTION FOR READING  MANUVALLY ENTERED */
                            $details_given    = true;
                            $header_not_found = false;
                            $variable         = LocationSettings::getVariable();
                            
                            foreach ($variable as $availabl) {
                                $available_variable[] = $availabl['variable'];
                            }
                            
                            
                            foreach ($available_variable as $variabl) {
                                $varible_value[$variabl] = $this->post($variabl);
                                $count                   = count($this->post($variabl));
                            }
                            
                            
                            $pages          = array();
                            $pag            = array();
                            $variable_count = count($variable);
                            for ($i = 1; $i <= $count; $i++) {
                                $k = 0;
                                foreach ($available_variable as $var) {
                                    $pag[$k] = $varible_value[$var][$i];
                                    $k++;
                                }
                                $pages[$i] = $pag;
                            }
                            
                            
                            foreach ($available_variable as $var) {
                                $key                 = array_search($var, $available_variable);
                                $replace_value[$var] = $key;
                            }
                            
                            /*----------------------------------------------------        */
                            //print_r($replace_value);
                            // print_r($pages);
                            //echo $this->post('internalLinkCID');
                            //die;
                            //print_r($available_variable);
                            $locations = explode("\n", trim($getPages));
                            //                        foreach($locations as $location){
                            //                            $pages[] = explode(",",$location);
                            //                     }
                            /*-------------------------------------------------------------     */
                            
                            
                            /* END*/
                        }
                        
                        
                        if ($details_given == false) {
                            $this->error->add(t('You must choose any one option for add details'));
                        }
                        
                        
                        
                        if ((!$this->error->has())) {
                            
                            
                            $parent_page_object->setAttribute('service_meta_title', $metaTitle);
                            $parent_page_object->setAttribute('service_meta_description', $metaDescription);
                            $parent_page_object->setAttribute('service_meta_keywords', $metaTags);
                            $parent_page_object->setAttribute('additional_service_content', $defaultContent);
                            $parent_page_object->setAttribute('service_page_title', $pageTitle);
                            
                            foreach ($pages as $page) {
                                if (($page[$replace_value['state']] != 'state') && ($page[$replace_value['city']] != 'city')) {
                                    $data = array(
                                        'cName' => trim($page[$replace_value['city']] . ', ' . $page[$replace_value['state']])
                                    );
                                    
                                    
                                    $p = $parent->add($ct, $data, $pt);
                                    
                                    
                                    $metaTitle_modified       = $this->replace_content($page, $replace_value, $metaTitle);
                                    $metaDescription_modified = $this->replace_content($page, $replace_value, $metaDescription);
                                    $metaTags_modified        = $this->replace_content($page, $replace_value, $metaTags);
                                    $defaultContent_modified  = $this->replace_content($page, $replace_value, $defaultContent);
                                    $pageTitle_modified       = $this->replace_content($page, $replace_value, $pageTitle);
                                    
                                    
                                    foreach ($available_variable as $variable) {
                                        
                                        $page_variable_with_value[$variable] = $page[$replace_value[$variable]];
                                        
                                    }
                                    
                                    if (is_object($p)) {
                                        
                                        
                                        
                                        $p->setAttribute('meta_title', $metaTitle_modified);
                                        $p->setAttribute('meta_description', $metaDescription_modified);
                                        $p->setAttribute('meta_keywords', $metaTags_modified);
                                        $p->setAttribute('service_content', $defaultContent_modified);
                                        $p->setAttribute('page_variable_value', serialize($page_variable_with_value));
                                        $p->setAttribute('page_title', $pageTitle_modified);
                                        if (!empty($fileset_id)) {
                                            $p->setAttribute('fileset_info', $fileset_id);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                
                if (isset($_POST['SERVICE_LOCATIONS_PAGE_TEMPLATE_ID'])) {
                    $pkg->getConfig()->save('service.SERVICE_LOCATIONS_PAGE_TEMPLATE_ID', $_POST['SERVICE_LOCATIONS_PAGE_TEMPLATE_ID']);
                }
                
                if (isset($_POST['SERVICE_LOCATIONS_COLLECTION_TYPE_ID'])) {
                    $pkg->getConfig()->save('service.SERVICE_LOCATIONS_COLLECTION_TYPE_ID', $_POST['SERVICE_LOCATIONS_COLLECTION_TYPE_ID']);
                }
                $this->set('message', t('Locations/Settings has been saved.'));
                $this->view();
            }
        } else {
            $this->set('error', array(
                $this->token->getErrorMessage()
            ));
        }
    }
    public function on_before_render()
    {
        $this->set('error', $this->error);
    }
    public function save_variable()
    {
        $variables = $this->post('variables');
		
		$obj = new \Stdclass;
		
        $obj->error = true;
		$obj->variables = array();
        if (!empty($variables)) {
            $variable = array_filter($variables);
            $responses = LocationSettings::saveVariable($variable);
			$obj->error = false;
			foreach($responses as $response){
				array_push($obj->variables,'{{'. $response .'}}');
			}
			$obj->responses = $responses;
		}
		echo json_encode($obj);
		exit();
    }
    
    public function get_defalut_content($id)
    {
        $parent_page_object                  = Page::getByID($id);
        $default_content                     = array();
        $default_content['service_content']  = $parent_page_object->getAttribute('additional_service_content');
        $default_content['meta_title']       = $parent_page_object->getAttribute('service_meta_title');
        $default_content['meta_description'] = $parent_page_object->getAttribute('service_meta_description');
        $default_content['meta_keywords']    = $parent_page_object->getAttribute('service_meta_keywords');
        $default_content['page_titles']      = $parent_page_object->getAttribute('service_page_title');
        echo json_encode($default_content);
        die;
    }
    
    public function get_available_variable()
    {
        
        $variable = LocationSettings::getVariable();
        
        foreach ($variable as $availabl) {
            $available_variable[] = $availabl['variable'];
        }
        return $available_variable;
        
    }
    
    public function replace_content_on_update($content, $value)
    {
        $available_variable = $this->get_available_variable();
        $i                  = 0;
        foreach ($available_variable as $variable) {
            $matche = "{{" . $variable . "}}";
            if ($i == 0) {
                $content_modi = preg_replace('/' . $matche . '/', $value[$variable], $content);
                $i++;
            } elseif ($i == 1) {
                $content_modified = preg_replace('/' . $matche . '/', $value[$variable], $content_modi);
                $i++;
            }
            $content_modified = preg_replace('/' . $matche . '/', $value[$variable], $content_modified);
        }
        if (count($available_variable) == 0) {
            
            return $content;
            
        } elseif (count($available_variable) == 1) {
            
            return $content_modi;
            
        } else {
            
            return $content_modified;
            
        }
        
    }
    
    
    public function replace_content($page, $replace_value, $content)
    {
        
        $available_variable = $this->get_available_variable();
        $i                  = 0;
        foreach ($available_variable as $variable) {
            $matche = "{{" . $variable . "}}";
            if ($i == 0) {
                $content_modi = preg_replace('/' . $matche . '/', $page[$replace_value[$variable]], $content);
                $i++;
            } elseif ($i == 1) {
                $content_modified = preg_replace('/' . $matche . '/', $page[$replace_value[$variable]], $content_modi);
                $i++;
            }
            $content_modified = preg_replace('/' . $matche . '/', $page[$replace_value[$variable]], $content_modified);
        }
        if (count($available_variable) == 0) {
            
            return $content;
            
        } elseif (count($available_variable) == 1) {
            
            return $content_modi;
            
        } else {
            
            return $content_modified;
            
        }
        
        
    }
    
    
}