<?php /*?><?php  
defined('C5_EXECUTE') or die(_("Access Denied.")); 
class DashboardServiceLocationsController extends Controller {
	
	public function view() {
		$this->redirect('/dashboard/service_locations/list/');
	}
	
	
	public $helpers = array('html','form');
	
	public function on_start() {
		$subnav = array(
			array(View::url('/dashboard/service_locations'),t('Service Locations'), true), 
			array(View::url('/dashboard/service_locations/settings'),'Settings')
		);
		$this->set('subnav', $subnav);
		Loader::model('page_list');
		$this->error = Loader::helper('validation/error');
	}
}<?php */?>