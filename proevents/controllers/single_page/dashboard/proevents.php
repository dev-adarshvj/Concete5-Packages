<?php  
namespace Concrete\Package\Proevents\Controller\SinglePage\Dashboard;

use \Concrete\Core\Page\Controller\DashboardPageController;
use Loader;

class Proevents extends DashboardPageController
{

    public function view()
    {
        $this->redirect('/dashboard/proevents/event_list/');
    }

}