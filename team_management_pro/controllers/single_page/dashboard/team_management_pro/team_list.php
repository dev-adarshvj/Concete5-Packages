<?php
namespace Concrete\Package\TeamManagementPro\Controller\SinglePage\Dashboard\TeamManagementPro;

use \Concrete\Core\Page\Controller\DashboardPageController;
use Loader;
use PageList;
use PageTemplate;
use Page;

defined('C5_EXECUTE') or die(_("Access Denied."));
class TeamList extends DashboardPageController {

	public $itemsPerPage=1;
	public $num = 1;

	public $helpers = array('html','form');

	public function on_start() {
		$this->error = Loader::helper('validation/error');
		 $this->set('pageTitle','Team List');
	}

	public function view() {


		if(isset($_GET['action']) and $_GET['action'] == 'save_order'){
			$i = 1;
			foreach($_POST['pageid'] as $pge){
				$newPage = Page::getByID($pge);
				$newPage->setAttribute('listing_order', $i);
				$i++;
			}
		}

		$this->loadTeamManagementSections();
		$pageList = new PageList();
		$itemsperpage=10;
		if($_GET['ccm_order_dir']=='asc'){
			$ccm_order_dir='desc';
		}else{
			//$ccm_order_dir='asc';
		}
		$this->set('ccm_order_dir',$ccm_order_dir);
		/*STATUS FILTER*/
		if($_GET['ccm_order_by'] && $_GET['ccm_order_dir']){
		$pageList->sortBy('ak_'.$_GET['ccm_order_by'], $_GET['ccm_order_dir']);

		}else{
			//$pageList->sortBy('cDateAdded', 'desc');

		//	$pageList->sortBy('ak_listing_order', 'asc');
			//$clinicList->sortBy('ak_clinic_order', 'asc');
			//$pageList->sortBy('cID', 'asc');
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
			/*	NAME FILTER*/
		if($_GET['ccm_order_dir_name']=='asc'){
			$ccm_order_dir_name='desc';
			$pageList->sortBy($_GET['ccm_order_by_name'], $_GET['ccm_order_dir_name']);
		}elseif($_GET['ccm_order_dir_name']=='desc'){
			$ccm_order_dir_name='asc';
			$pageList->sortBy($_GET['ccm_order_by_name'], $_GET['ccm_order_dir_name']);
			}

		$this->set('ccm_order_dir_name',$ccm_order_dir_name);
		/*NAME*/
		/*SEction	FILTER*/
		if($_GET['ccm_order_dir_section']=='asc'){
			$ccm_order_dir_date='desc';
			$pageList->sortBy('pp.cPath', 'asc');
		}elseif($_GET['ccm_order_dir_section']=='desc'){
			$ccm_order_dir_date='asc';
			$pageList->sortBy('pp.cPath', 'desc');
			}
		$this->set('ccm_order_dir_date',$ccm_order_dir_date);
		/*SEction	*/

		if ($_GET['numResults']>0) {
		$pageList->setItemsPerPage($_GET['numResults']);
		$numResults=$_GET['numResults'];
		}else{
		$pageList->setItemsPerPage($itemsperpage);
		$numResults=$itemsperpage;
		}
		 $totalPages=count($pageList->getResults());

 		 $showPagination = false;
		 $pagination = $pageList->getPagination();
		 $pages = $pagination->getCurrentPageResults();
		 $pagination = $pagination->renderDefaultView();
         if($totalPages > $numResults || $_REQUEST['ccm_paging_p']){
		 $showPagination = true;
		 $this->set('pagination', $pagination);
		 }

		 if ($showPagination) {
            $this->requireAsset('css', 'core/frontend/pagination');
        }

        //$this->set('pageList', $pages);
        $this->set('pageResults', $pages);
        $this->set('showPagination', $showPagination);
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

	public function delete_check($cIDd,$name) {
		$this->set('remove_name',$name);
		$this->set('remove_cid',$cIDd);
		$this->view();
	}

	public function delete($cIDd,$name) {
		$c= Page::getByID($cIDd);
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

	public function page_added() {
		$this->set('message', t('Team added.'));
		$this->view();
	}

	public function page_updated() {
		$this->set('message', t('Team updated.'));
		$this->view();
	}

	public function page_deleted() {
		$this->set('message', t('Team deleted.'));
		$this->view();
	}

	public function on_before_render() {
		$this->set('error', $this->error);
	}

}
