<?php  
namespace Concrete\Package\Proevents\Controller\Tools;

use Loader;
use Page;
use Block;
use Concrete\Core\Controller\Controller as RouteController;
use \Concrete\Package\Proevents\Src\ProEvents\EventItem;

class GeneratedEvent extends RouteController
{

    public function edit()
    {
        Loader::PackageElement('tools/edit_generated_event', 'proevents');
    }

}