<?php  
namespace Concrete\Package\Proevents\Controller\Tools;

use Loader;
use Page;
use Block;
use Concrete\Core\Controller\Controller as RouteController;
use \Concrete\Package\Proevents\Src\ProEvents\EventItem;

class Excludedate extends RouteController
{

    public function render()
    {
        Loader::PackageElement('tools/excludedate', 'proevents');
    }

}