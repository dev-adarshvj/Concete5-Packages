<?php  
namespace Concrete\Package\ServiceLocations\Controller\SinglePage\Dashboard\ServiceLocations;
use \Concrete\Core\Page\Controller\DashboardPageController;
use \Concrete\Core\Attribute\Key\Category as AttributeKeyCategory;
use CollectionAttributeKey;
use Loader;
use PageList;
use AttributeSet;
use Page;
class ServiceList extends DashboardPageController {
	
	public $num = 15;
	
	public $helpers = array('html','form');
	
	public function on_start() {
	//	die(fjf);
		
		$this->error = Loader::helper('validation/error');
		 $this->set('pageTitle','Service List');
	}
	
	public function view() {
	
		//echo jhg;die;
		$this->loadServiceLocationsSections();
		$pageList = new PageList();
		if($_GET['ccm_order_by'] && $_GET['ccm_order_dir']){
			
			$inventorySectionList->filter(false, "(ak_pi_unit_id  ORDER BY asc)");
			
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
		if(!empty($_GET['like'])){
			$pageList->filterByName($_GET['like']);
			}
		$this->set('pageList', $pageList);
		$this->set('pageResults', $pageList->getResults());
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

	public function delete_check($cIDd,$name) {
		$this->set('remove_name',$name);
		$this->set('remove_cid',$cIDd);
		$this->view();
	}
	
	public function delete($cIDd,$name) {
		$c= Page::getByID($cIDd);
		$db = Loader::db();
		$c->delete();
		$this->set('message', t('"'.$name.'" has been deleted')); 
		$this->set('remove_name','');
		$this->set('remove_cid','');
		$this->view();
	}
	
	public function duplicate($cIDd){
		$c = Page::getByID($cIDd);
		$cpID = $c->getCollectionParentID();
		$cp = Page::getByID($cpID);
		$c->duplicate($cp);
		$this->view();
	}

	public function clear_warning(){
		$this->set('remove_name','');
		$this->set('remove_cid','');
		$this->view();
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
		
		if (!$vt->notempty($this->post('page_title'))) {
			$this->error->add(t('Title is required'));
		}

		if (!$this->error->has()) {
			Loader::model('collection_types');
			$ct = CollectionType::getByID($this->post('ctID'));				
			$parent = Page::getByID($this->post('cParentID'));				
			$parentPermissions = new Permissions($parent);
			if (!$parentPermissions->canAddSubCollection($ct)) {
				$this->error->add(t('You do not have permission to add a page of that type to that area of the site.'));
			}
		}
	}
	
	private function saveData($p) {
			
		Loader::model("attribute/categories/collection");
		
		$set = AttributeSet::getByHandle('page');
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

	public function page_added() {
		$this->set('message', t('Property added.'));
		$this->view();
	}
	
	public function page_updated() {
		$this->set('message', t('Property updated.'));
		$this->view();
	}
	
	public function page_deleted() {
		$this->set('message', t('Property deleted.'));
		$this->view();
	}	
	
	public function on_before_render() {
		$this->set('error', $this->error);
	}
	
}