<?php  
namespace Concrete\Package\TeamManagementPro\Controller\SinglePage\Dashboard;

use \Concrete\Core\Page\Controller\DashboardPageController;

defined('C5_EXECUTE') or die(_("Access Denied."));

class TeamManagementPro extends DashboardPageController {
	/**
	* Dashboard view - automatically redirects to a default
	* page in the category
	*
	* @return void
	*/
	public function view() {
		$this->redirect('/dashboard/team_management_pro/team_list/');
	}
}
