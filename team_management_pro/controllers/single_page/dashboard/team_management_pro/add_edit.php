<?php
namespace Concrete\Package\TeamManagementPro\Controller\SinglePage\Dashboard\TeamManagementPro;

use \Concrete\Core\Page\Controller\DashboardPageController;
use \Concrete\Core\Attribute\Key\Category as AttributeKeyCategory;
use CollectionAttributeKey;
use Loader;
use Block;
use Config;
use PageList;
use BlockType;
use TaskPermission;
use PageType;
use PageTemplate;
use Page;
use Permissions;
use AttributeSet;

defined('C5_EXECUTE') or die(_("Access Denied."));
class AddEdit extends DashboardPageController {

	public $num = 15;

	public $helpers = array('html','form');

	public function on_start() {
		//Loader::model('page_list');
		$this->error = Loader::helper('validation/error');
		 $this->set('pageTitle','Add/Edit');
	}

	public function view() {
		$this->setupForm();
		$this->loadTeamManagementSections();
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
		$this->set('pageResults', $pageList->getResults());
	}

	protected function loadTeamManagementSections() {
		$pageSectionList = new PageList();
		$pageSectionList->filterByTeamManagementSection(1);
		$pageSectionList->sortBy('cvName', 'asc');
		$tmpSections = $pageSectionList->get();
		$sections = array();
		foreach($tmpSections as $_c) {
			if($_c->getCollectionAttributeValue('team_management_section')){
				$sections[$_c->getCollectionID()] = $_c->getCollectionName();
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
			$this->redirect('/dashboard/team_management_pro/');
		}
	}

	public function delete($cID) {
		$this->setupForm();
		$page = Page::getByID($cID);
		$sections = $this->get('sections');
		if (in_array($page->getCollectionParentID(), array_keys($sections))) {
			$this->set('page', $page);
		} else {
			$this->redirect('/dashboard/team_management_pro/team_list');
		}
	}

	protected function setupForm() {
		$this->loadTeamManagementSections();
		$ctArray = PageType::getList();
		$pageTypes = array();
		foreach($ctArray as $ct) {
			if($ct->getPageTypeName()!='Home'){
			$pageTypes[$ct->getPageTypeID()] = $ct->getPageTypeName();
			}
		}
		$this->set('PageTemplates', $PageTemplates);
		$this->addHeaderItem(Loader::helper('html')->javascript('tiny_mce/tiny_mce.js'));
	}

	public function add() {

		$this->setupForm();
		if ($this->isPost()) {
			$this->validate();
			if (!$this->error->has()) {
				$parent = Page::getByID($this->post('cParentID'));
				$ct = PageType::getByID($this->post(Config::get('concrete.page_template_id')));
				$pt = PageTemplate::getByID(intval(Config::get('concrete.page_template_id')));
				$data = array('cName' => $this->post('page_title'), 'cDescription' => $this->post('pageDescription'), 'cDatePublic' => Loader::helper('form/date_time')->translate('page_date_time'));
				$p = $parent->add($ct, $data,$pt);
				$this->saveData($p);
				$this->redirect('/dashboard/team_management_pro/team_list', 'page_added');
			}
		}
	}

	public function update($cID) {
		$this->edit($this->post('pageID'));

		if ($this->isPost()) {
			$this->validate();
			if (!$this->error->has()) {
				$p = Page::getByID($this->post('pageID'));
				$parent = Page::getByID($this->post('cParentID'));
				$ct = PageType::getByID($this->post('ctID'));
				$data = array('pTemplateID' =>$this->post('ptID'),'ctID' =>$ct->getPageTypeID(), 'cDescription' => $this->post('pageDescription'), 'cName' => $this->post('page_title'), 'cDatePublic' => Loader::helper('form/date_time')->translate('page_date_time'));
				$p->update($data);
				if ($p->getCollectionParentID() != $parent->getCollectionID()) {
					$p->move($parent);
				}
				$this->saveData($p);
				$this->redirect('/dashboard/team_management_pro/team_list', 'page_updated');
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
			$this->error->add(t('You must choose a page Template from settings for this Page entry.'));
		}
		if (!$vn->integer($this->post('ptID'))) {
			$this->error->add(t('You must choose a page type   from settings for this Page entry.'));
		}

		if (!$vt->notempty($this->post('page_title'))) {
			$this->error->add(t('Title is required'));
		}

		if (!$this->error->has()) {

			$ct = PageType::getByID($this->post('ctID'));
			$parent = Page::getByID($this->post('cParentID'));
			$parentPermissions = new Permissions($parent);
			if (!$parentPermissions->canAddSubCollection($ct)) {
				$this->error->add(t('You do not have permission to add a page of that type to that area of the site.'));
			}
		}
	}

	private function saveData($p) {
			$blocks = $p->getBlocks('Main');
		foreach($blocks as $b) {
			$b->deleteBlock();
		}
	$attributeset_id=Config::get('concrete.team_management_attribute_set_id');
    $set = AttributeSet::getByID($attributeset_id);
	if(is_object($set)){
		$setAttribs = $set->getAttributeKeys();
		if($setAttribs){
			foreach ($setAttribs as $ak) {
				$ak = CollectionAttributeKey::getByHandle($ak->getAttributeKeyHandle());
							$controller = $ak->getController();
							$value = $controller->createAttributeValueFromRequest();
							$p->setAttribute($ak, $value);
			}
		}
	}
/*	$contents='';
	$attributeset_id=Config::get('concrete.news_management_attribute_set_id');
    $set = AttributeSet::getByID($attributeset_id);
	if(is_object($set)){
		$setAttribs = $set->getAttributeKeys();
		if($setAttribs){
			foreach ($setAttribs as $ak) {
				if($ak->atID==3){
					$val=$p->getCollectionAttributeValue($ak->akHandle)?'Yes':'No';
					$contents.='<strong>'.$ak->akName.'</strong> : '.$val.'<br>';
				}elseif($ak->atID==5){
					if(is_object($p->getCollectionAttributeValue($ak->akHandle))){
						$contents.='<strong>'.$ak->akName.'</strong> : <img src="'.$p->getCollectionAttributeValue($ak->akHandle)->getRelativePath().' />"<br>';
					}
				}else{
					$contents.='<strong>'.$ak->akName.'</strong> : '.$p->getCollectionAttributeValue($ak->akHandle).'<br>';
				}


			}
		}
	}*/

	//$bt = BlockType::getByHandle('content');

		//$data = array('content' => $contents);

		//$p->addBlock($bt, 'Main', $data);

		//$p->reindex();
	}
	public function on_before_render() {
		$this->set('error', $this->error);
	}



	}
