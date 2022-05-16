<?php  
namespace Concrete\Package\ServiceLocations\Controller\SinglePage\Dashboard;
use \Concrete\Core\Page\Controller\DashboardPageController;

defined('C5_EXECUTE') or die(_("Access Denied.")); 

class ServiceLocations extends DashboardPageController {

	public function view() {
		$this->redirect('/dashboard/service_locations/service_list');
	}
	
}