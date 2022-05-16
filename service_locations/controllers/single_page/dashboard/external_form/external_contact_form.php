<?php
namespace Concrete\Package\ServiceLocations\Controller\SinglePage\Dashboard\ExternalForm;
use \Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Package\ServiceLocations\Src\Entity\SaveContactListing;
use Concrete\Package\ServiceLocations\Src\Entity\SaveContact;

class ExternalContactForm  extends DashboardPageController{

    public function view(){
       
        $c = new SaveContactListing();
//        if(isset($_GET['keywords']) and trim($_GET['keywords']) !='') {
//        $c->filterByKeywords($_GET['keywords']);
//		}
//        
      
   $data = $c->get();
        $this->set('data',$data);
        
        


    }
    
 
}
