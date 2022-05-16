<?php  
namespace Concrete\Package\Proevents\Controller\Tools;

use Loader;
use Page;
use View;
use Block;
use Concrete\Core\Controller\Controller as RouteController;
use \Concrete\Package\Proevents\Src\ProEvents\EventItem;

class ColorCategory extends RouteController
{

    public function render()
    {
        Loader::PackageElement('tools/color_category', 'proevents');
    }

}