<?php
namespace Concrete\Package\TeamManagementPro\Controller\SinglePage\Dashboard\TeamManagementPro;

use \Concrete\Core\Attribute\Key\Category as AttributeKeyCategory;
use \Concrete\Core\Page\Controller\DashboardPageController;
use AttributeSet;
use Loader;
use Config;
use PageTemplate;
use PageType;

class TeamSettings extends DashboardPageController {

  public function view(){
		 $category=AttributeKeyCategory::getByID(1);
	  $sets = $category->getAttributeSets();
	  $setsarr=array();
	  foreach($sets as $set){
		$setsarr[$set->getAttributeSetID()]=$set->getAttributeSetName();
		}
		$this->set('attribute_sets', $setsarr);
	$ctArray = PageTemplate::getList();
		$PageTemplates = array(''=>'Select Page Template');
		foreach($ctArray as $ct) {
			$PageTemplates[$ct->getPageTemplateID()] = $ct->getPageTemplateName();
		}
	$this->set('PageTemplates', $PageTemplates);
		$ctArray = PageType::getList();
		$PageTypes = array(''=>'Select Page Type');
		foreach($ctArray as $ct) {
			$PageTypes[$ct->getPageTypeID()] = $ct->getPageTypeName();
		}
	$this->set('attribute_sets', $setsarr);
	$ctArray = PageType::getList();
		$pageTypes = array(''=>'Select Page Type');
		foreach($ctArray as $ct) {
			$pageTypes[$ct->getPageTypeID()] = $ct->getPageTypeName();
		}
	$this->set('pageTypes', $pageTypes);
	$this->set('attribute_set_id',Config::get('concrete.team_management_attribute_set_id'));
	$this->set('page_template_id',Config::get('concrete.page_template_id'));
	$this->set('page_type_id',Config::get('concrete.team_management_collection_type_id'));
  }


  public function save_settings() {
		if ($this->token->validate("save_settings")) {
		if ($this->isPost()) {
		if(isset($_POST['TEAM_MANAGEMENT_ATTRIBUTE_SET_ID'])){
			Config::save('concrete.team_management_attribute_set_id', $_POST['TEAM_MANAGEMENT_ATTRIBUTE_SET_ID']);
		}
		if(isset($_POST['TEAM_MANAGEMENT_PAGE_TEMPLATE_ID'])){
			Config::save('concrete.page_template_id', $_POST['TEAM_MANAGEMENT_PAGE_TEMPLATE_ID']);
		}
		if(isset($_POST['TEAM_MANAGEMENT_COLLECTION_TYPE_ID'])){
			Config::save('concrete.team_management_collection_type_id', $_POST['TEAM_MANAGEMENT_COLLECTION_TYPE_ID']);
		}
		$this->set('message', t('Settings has been saved.'));
		$this->view();
		}
		} else {
		$this->set('error', array($this->token->getErrorMessage()));
		}
	}



}
