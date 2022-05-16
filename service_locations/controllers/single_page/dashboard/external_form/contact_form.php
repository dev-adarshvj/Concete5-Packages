<?php
namespace Concrete\Package\ServiceLocations\Controller\SinglePage\Dashboard\ExternalForm;
use \Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Package\ServiceLocations\Src\Entity\SaveListing;
use Concrete\Package\ServiceLocations\Src\Entity\SaveForm;

class ContactForm  extends DashboardPageController{

    public function view(){
       
        $c = new SaveListing();
        if(isset($_GET['keywords']) and trim($_GET['keywords']) !='') {
        $c->filterByKeywords($_GET['keywords']);
		}
        
      
//      $data = $c->get();
//      $this->set('data',$data);
        
        $c->setItemsPerPage(5);
		$List = $c->getPage();
		$pagination = $c->getPagination();
		
		$this->set('requestList', $List);
        $this->set('data', $List);
		$this->set('paginator', $pagination);
		$this->set('fr',$c);
        

    }
    
 public function delete($id){
     
        $listingListObj = new SaveForm();
        $listing = $listingListObj->getTypeByID($id);
        $listingListObj->remove($listing);
		$this->redirect('/dashboard/external_form/contact_form','deleted');
		//$this->redirect('/dashboard/external_form_results/request_pricing_form', 'deleted');

     
}
    
public function deleted() {
		
		$this->set('message', t('Data Deleted.'));
		$this->view();
		
		
		}
//  
//    

}
