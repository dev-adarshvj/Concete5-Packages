<?php
namespace Concrete\Package\ServiceLocations\Controller\SinglePage\Dashboard\ServiceLocations;
use \Concrete\Core\Page\Controller\DashboardPageController;
use \Concrete\Core\Attribute\Key\Category as AttributeKeyCategory;
use CollectionAttributeKey;
use Loader;
use PageList;
use TaskPermission;
use Environment;
use Exception;
use Package;
use SinglePage;
use Page;
use Permissions;
use AttributeSet;
use Config;
use BlockType;

use PageTemplate;
defined('C5_EXECUTE') or die(_("Access Denied."));
class AddEdit extends DashboardPageController {
	public $num = 15;

	public $helpers = array('html','form');

	public function on_start() {

		$this->error = Loader::helper('validation/error');
		 $this->set('pageTitle','Add Edit');
	}

	public function view() {
		//$pbp = SinglePage::add("/dashboard/service_locations/service_settings", Package::getByHandle('service_locations'));

		$this->setupForm();
		$this->loadServiceLocationsSections();
		$pageList = new PageList();
		if($_GET['ccm_order_by'] && $_GET['ccm_order_dir']){
			$pageList->sortBy('ak_'.$_GET['ccm_order_by'], $_GET['ccm_order_dir']);
		}else{
			$pageList->sortBy('cDateAdded', 'desc');
		}

		if (isset($_GET['cParentID']) && $_GET['cParentID'] > 0) {
			$pageList->filterByParentID($_GET['cParentID']);
		} else {
			$sections = $this->get('sections');
			$keys = array_keys($sections);
			$keys[] = -1;
			$pageList->filterByParentID($keys);
		}
		$this->set('pageList', $pageList);
		$this->set('pageResults', $pageList->get());
	}

	protected function loadServiceLocationsSections() {
		$pageSectionList = new PageList();
		$pageSectionList->filterByServiceLocationsSection(1);
		$pageSectionList->sortBy('cvName', 'asc');
		$tmpSections = $pageSectionList->get();
		$sections = array();
		foreach($tmpSections as $_c) {
			if($_c->getCollectionAttributeValue('service_locations_section')){

				  if($_c->cParentID>0){
		 $ppname=Page::getByID($_c->cParentID)->getCollectionName().' - ';
		  }else{
			  $ppname='';
		  }
				$sections[$_c->getCollectionID()] = $ppname.$_c->getCollectionName();
			}


		}
		$this->set('sections', $sections);
	}


	public function edit($cID) {
		$this->setupForm();
		$page = Page::getByID($cID);
		$sections = $this->get('sections');
		if (in_array($page->getCollectionParentID(), array_keys($sections))) {
			$this->set('page', $page);
		} else {
			$this->redirect('/dashboard/service_locations/');
		}
	}

	public function delete($cID) {
		$this->setupForm();
		$page = Page::getByID($cID);
		$sections = $this->get('sections');
		if (in_array($page->getCollectionParentID(), array_keys($sections))) {
			$this->set('page', $page);
		} else {
			$this->redirect('/dashboard/service_locations/service_list');
		}
	}

	protected function setupForm() {
		$this->loadServiceLocationsSections();
		//Loader::model("collection_types");
		$ctArray = PageTemplate::getList();
		$pageTypes = array();
		foreach($ctArray as $ct) {
			if($ct->getPageTemplateName()!='Home'){
			$pageTypes[$ct->getPageTemplateID()] = $ct->getPageTemplateName();
			}
		}
		$this->set('pageTypes', $pageTypes);
		$this->addHeaderItem(Loader::helper('html')->javascript('tiny_mce/tiny_mce.js'));
	}

	public function add() {
		$this->setupForm();
		if ($this->isPost()) {
			$this->validate();
			if (!$this->error->has()) {

				$parent = Page::getByID($this->post('cParentID'));
				$ct = PageTemplate::getByID($this->post('ctID'));
				$data = array('cName' => $this->post('pageTitle'), 'cDescription' => $this->post('pageDescription'), 'cDatePublic' => Loader::helper('form/date_time')->translate('page_date_time'));				$p = $parent->add($ct, $data);
					$this->saveData($p);
				$this->redirect('/dashboard/service_locations/service_list', 'page_added');
			}
		}
	}

	public function update($id) {
		$this->edit($this->post('pageID'));

		if ($this->isPost()) {
			$this->validate();
			if (!$this->error->has()) {
				$p = Page::getByID($this->post('pageID'));
				$parent = Page::getByID($this->post('cParentID'));
				$ct = PageTemplate::getByID($this->post('ctID'));
				$data = array('ctID' =>$ct->getPageTemplateID(), 'cDescription' => $this->post('pageDescription'), 'cName' => $this->post('pageTitle'), 'cDatePublic' => Loader::helper('form/date_time')->translate('page_date_time'));
				$p->update($data);
				if ($p->getCollectionParentID() != $parent->getCollectionID()) {
					$p->move($parent);
				}
				$this->saveData($p);
				$this->redirect('/dashboard/service_locations/service_list', 'page_updated');
			}
		}
	}

protected function validate() {
		$vt = Loader::helper('validation/strings');
		$vn = Loader::Helper('validation/numbers');
		$dt = Loader::helper("form/date_time");
		if (!$vn->integer($this->post('cParentID'))) {
			$this->error->add(t('You must choose a parent page for this Page entry.'));
		}

		if (!$vn->integer($this->post('ctID'))) {
			$this->error->add(t('You must choose a page type for this Page entry.'));
		}

		if (!$vt->notempty($this->post('pageTitle'))) {
			$this->error->add(t('Title is required'));
		}

		if (!$this->error->has()) {
			Loader::model('collection_types');
			$ct = PageTemplate::getByID($this->post('ctID'));
			$parent = Page::getByID($this->post('cParentID'));
			$parentPermissions = new Permissions($parent);
			if (!$parentPermissions->canAddSubCollection($ct)) {
				$this->error->add(t('You do not have permission to add a page of that type to that area of the site.'));
			}
		}
	}

	private function saveData($p) {
		$pkg = Package::getByHandle("service_locations");
		/*	$blocks = $p->getBlocks('Main');
		foreach($blocks as $b) {
			$b->deleteBlock();
		}*/
		Loader::model("attribute/categories/collection");
		//print_r($_POST);die;
	$attributeset_id=$pkg->getConfig()->get('service.SERVICE_LOCATIONS_ATTRIBUTE_SET_ID');
    $set = AttributeSet::getByID($attributeset_id);
	if(is_object($set)){
		$setAttribs = $set->getAttributeKeys();
		if($setAttribs){
			foreach ($setAttribs as $ak) {
				//$aksv = CollectionAttributeKey::getByHandle($ak->getAttributeKeyHandle());
				//$aksv->saveAttributeForm($p);
				$ak = CollectionAttributeKey::getByHandle($ak->getAttributeKeyHandle());
        $controller = $ak->getController();
        $value = $controller->createAttributeValueFromRequest();
        $p->setAttribute($ak, $value);
			}
		}
	}
	$contents='';
	$attributeset_id=$pkg->getConfig()->get('service.SERVICE_LOCATIONS_ATTRIBUTE_SET_ID');
    $set = AttributeSet::getByID($attributeset_id);
	if(is_object($set)){
		$setAttribs = $set->getAttributeKeys();
		if($setAttribs){
			foreach ($setAttribs as $ak) {
				if($ak->atID==3){
					$val=$p->getCollectionAttributeValue($ak->getAttributeKeyHandle())?'Yes':'No';
					$contents.='<strong>'.$ak->getAttributeKeyName().'</strong> : '.$val.'<br>';
				}elseif($ak->atID==5){
					if(is_object($p->getCollectionAttributeValue($ak->getAttributeKeyHandle()))){
						$contents.='<strong>'.$ak->getAttributeKeyName().'</strong> : <img src="'.$p->getCollectionAttributeValue($ak->getAttributeKeyHandle())->getRelativePath().' />"<br>';
					}
				}else{
					$contents.='<strong>'.$ak->getAttributeKeyName().'</strong> : '.is_object($p->getCollectionAttributeValue($ak->getAttributeKeyHandle())).'<br>';
				}


			}
		}
	}

	//$bt = BlockType::getByHandle('content');

		//$data = array('content' => $contents);

		//$p->addBlock($bt, 'Main', $data);

		$p->reindex();
	}
	public function on_before_render() {
		$this->set('error', $this->error);
	}



	}
