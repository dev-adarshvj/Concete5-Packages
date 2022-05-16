<?php  
namespace Concrete\Package\ServiceLocations;
use \Concrete\Core\Attribute\Type as AttributeType;
use \Concrete\Core\Attribute\Key\Category as AttributeKeyCategory;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionKey;
use \Concrete\Core\Support\Facade\Application;
use CollectionAttributeKey;
use Package;
use BlockType;
use SinglePage;
use Loader;
use Config;
use Page;  
use PageTemplate;  
use FileSet; 
use Exception;
use AssetList;
use Asset;
defined('C5_EXECUTE') or die(_("Access Denied."));

class Controller extends Package {

	protected $pkgHandle = 'service_locations';
	protected $appVersionRequired = '5.6.0';
	protected $pkgVersion = '0.9.10';
	
	public function getPackageDescription() {
		return t("To Manage service locations");
	}
	
	public function getPackageName() {
		return t("Service Locations");
	}
	
	public function uninstall(){
		
		$pkg = Package::getByHandle("service_locations");
		
		/*
		DELETE ALL ASSOCIATED ATTRIBUTES
		*/
		
		$app = Application::getFacadeApplication();
		$em = $app->make('database')->getEntityManager();
        $db = $app->make('database')->connection();
		
		
		$akIDs = $db->getAll('select akID from AttributeKeys where pkgID = ?',array($pkg->getPackageID()));
      
		foreach($akIDs as $akID){/*
			try{
				$at = CollectionKey::getByID($akID['akID']);	
				$em->remove($at);
				$em->flush();
			}catch(\Exception $e){
				echo $e->getMessage();
			}
		*/}
		
		parent::uninstall();
	}
	public function upgrade() {
     	$pkg = Package::getByHandle("service_locations");
    	 $this->load_required_models();
	 	$this->install_dp_attributes($pkg);
   	 	 parent::upgrade();
	  }
	  
	public function install() {
		
		$this->load_required_models();
		$pkg = parent::install();
		$this->install_dp_singlepages($pkg);
		$this->install_dp_attributes($pkg);
		$this->install_dp_pages($pkg);
		$this->install_dp_settings($pkg);
        
	
	}
	
	
	function install_dp_singlepages($pkg){
		
		if(Page::getByPath("/dashboard/service_locations")){
			$pbp = SinglePage::add("/dashboard/service_locations", $pkg);
		}
			if(Page::getByPath("/dashboard/service_locations/service_list")){
			$pbp = SinglePage::add("/dashboard/service_locations/service_list", $pkg);
		}
		if(Page::getByPath("/dashboard/service_locations/add_edit")){
			$pbp = SinglePage::add("/dashboard/service_locations/add_edit", $pkg);
		}
			
			if(Page::getByPath("/dashboard/service_locations/service_settings")){
			$pbp = SinglePage::add("/dashboard/service_locations/service_settings", $pkg);
		}
        
//        	if(Page::getByPath("/dashboard/service_locations/contact_form")){
//			$pbp = SinglePage::add("/dashboard/service_locations/contact_form", $pkg);
//		}
        

			
	}
  
	function install_dp_attributes($pkg) {
		$pkg = Package::getByHandle("service_locations");
		
		$checkn = AttributeType::getByHandle('boolean'); 
		
		
			$eaku = AttributeKeyCategory::getByHandle('collection');
			$eaku->setAllowAttributeSets(AttributeKeyCategory::ASET_ALLOW_SINGLE);
			$evset = $eaku->addSet('service_locations', t('Service Locations'),$pkg);
			$pkg->getConfig()->save('concrete.service_locations_attribute_set_id',$evset->getAttributeSetID());
			$filesetAttrType = AttributeType::getByHandle('fileset');
		if(!is_object($filesetAttrType) || !intval($filesetAttrType->getAttributeTypeID()) ) { 
		$col = AttributeKeyCategory::getByHandle('collection');
			$filesetAttrType = AttributeType::add('fileset', t('Fileset'), $pkg); 
			$col->associateAttributeKeyType(AttributeType::getByHandle('fileset'));			  
		} 
		        $fileset = AttributeType::getByHandle('fileset');
				$fileset_info=CollectionAttributeKey::getByHandle('fileset_info');
				if( !is_object($fileset_info) ) {
				$fileset_info=CollectionAttributeKey::add($fileset, 
				array('akHandle'=>'fileset_info','akName'=>'Fileset Info','akIsSearchableIndexed'=>'1','akIsSearchable'=>'1','akTextareaDisplayMode'=>'rich_text_advanced'),$pkg)->setAttributeSet($evset); 
				}
			
				$textarea = AttributeType::getByHandle('textarea');
				$page_discription=CollectionAttributeKey::getByHandle('service_content');
				if( !is_object($page_discription) ) {
				$page_discription=CollectionAttributeKey::add($textarea, 
				array('akHandle'=>'service_content','akName'=>'Service Content','akIsSearchableIndexed'=>'1','akIsSearchable'=>'1','akTextareaDisplayMode'=>'rich_text_advanced'),$pkg)->setAttributeSet($evset); 
				}
				
				
				$textarea = AttributeType::getByHandle('textarea');
				$service_meta_keywords=CollectionAttributeKey::getByHandle('service_meta_keywords');
				if( !is_object($service_meta_keywords) ) {
				$service_meta_keywords=CollectionAttributeKey::add($textarea, 
				array('akHandle'=>'service_meta_keywords','akName'=>'Service Meta Keywords','akIsSearchableIndexed'=>'1','akIsSearchable'=>'1','akTextareaDisplayMode'=>'text'),$pkg); 
				}
				
				
				
				$textarea = AttributeType::getByHandle('textarea');
				$service_meta_description=CollectionAttributeKey::getByHandle('service_meta_description');
				if( !is_object($service_meta_description) ) {
				$service_meta_description=CollectionAttributeKey::add($textarea, 
				array('akHandle'=>'service_meta_description','akName'=>'Service Meta Description','akIsSearchableIndexed'=>'1','akIsSearchable'=>'1','akTextareaDisplayMode'=>'text'),$pkg); 
				}
				
				$textarea = AttributeType::getByHandle('textarea');
				$page_variable_value=CollectionAttributeKey::getByHandle('page_variable_value');
				if( !is_object($page_variable_value) ) {
				$page_variable_value=CollectionAttributeKey::add($textarea, 
				array('akHandle'=>'page_variable_value','akName'=>'Page Variable Value','akIsSearchableIndexed'=>'1','akIsSearchable'=>'1','akTextareaDisplayMode'=>'text'),$pkg); 
				}
				
				$textbox = AttributeType::getByHandle('text');
				$service_meta_title=CollectionAttributeKey::getByHandle('service_meta_title');
				if( !is_object($service_meta_title) ) {
				$service_meta_title=CollectionAttributeKey::add($textbox, 
				array('akHandle'=>'service_meta_title','akName'=>'Service Meta Title','akIsSearchableIndexed'=>'1','akIsSearchable'=>'1'),$pkg); 
				}
				
				
				
				$textarea = AttributeType::getByHandle('textarea');
				$additional_service_content=CollectionAttributeKey::getByHandle('additional_service_content');
				if( !is_object($additional_service_content) ) {
				$additional_service_content=CollectionAttributeKey::add($textarea, 
				array('akHandle'=>'additional_service_content','akName'=>'Additional Service Content','akIsSearchableIndexed'=>'1','akIsSearchable'=>'1','akTextareaDisplayMode'=>'rich_text_advanced'),$pkg); 
				}
				
				
				$textbox = AttributeType::getByHandle('text');
				$service_meta_title=CollectionAttributeKey::getByHandle('service_meta_title');
				if( !is_object($service_meta_title) ) {
				$service_meta_title=CollectionAttributeKey::add($textbox, 
				array('akHandle'=>'service_meta_title','akName'=>'Service Meta Title','akIsSearchableIndexed'=>'1','akIsSearchable'=>'1'),$pkg); 
				}
				
				$textbox = AttributeType::getByHandle('text');
				$page_title=CollectionAttributeKey::getByHandle('page_title');
				if( !is_object($page_title) ) {
				$page_title=CollectionAttributeKey::add($textbox, 
				array('akHandle'=>'page_title','akName'=>'Page Title','akIsSearchableIndexed'=>'1','akIsSearchable'=>'1'),$pkg)->setAttributeSet($evset); 
				}
				
	  	
	         	$textbox = AttributeType::getByHandle('text');
				$service_page_title=CollectionAttributeKey::getByHandle('service_page_title');
				if( !is_object($service_page_title) ) {
				$page_title=CollectionAttributeKey::add($textbox, 
				array('akHandle'=>'service_page_title','akName'=>'Service Page Title','akIsSearchableIndexed'=>'1','akIsSearchable'=>'1'),$pkg)->setAttributeSet($evset); 
				}
		
		
		
		
		$service_locations_section=CollectionAttributeKey::getByHandle('service_locations_section'); 
		if( !is_object($service_locations_section) ) {
			CollectionAttributeKey::add($checkn, 
			array('akHandle' => 'service_locations_section', 
			'akName' => t('Service Locations Section'),
			'akIsSearchable' => 1, 
			'akIsSearchableIndexed' => 1	
			),$pkg); 
		}
		
		}
  
	function install_dp_pages($pkg) {
			$pagetype = PageTemplate::getByHandle('services_details');
		if (!is_object($pagetype) || $pagetype==false) {
	  		$blogPageType = array('handle' => 'services_details', 'name' => t('Service Detail Page'));
      	//	PageType::add($blogPageType, $pkg);
			$pt = PageTemplate::add('services_details', 'Service Detail Page', 'left_sidebar.png',$pkg);
			}
		$setListAt = Page::getByPath('/service_locations_list');
		if(!is_object($setListAt) || $setListAt->getCollectionID()==null){
    		$pageeventParent = Page::getByID(HOME_CID);    	
    		$setListAt = $pageeventParent->add($pageType, array('cName' => 'Service Locations List', 'cHandle' => 'service_locations_list', 'pkgID'=>$pkg->getPackageID())); 
    		$setListAt->setAttribute('service_locations_section',true);
   		}else{
   			$setListAt->setAttribute('service_locations_section',true);
   		}
		
		$block = $setListAt->getBlocks('Main');
		foreach($block as $b) {
			$b->delete();
		}
		
		$bt_main = BlockType::getByHandle('page_list');
				
		$pagelist_block=$setListAt->addBlock($bt_main, 'Main', array(
		'num'=>10,
		'orderBy'=>'display_asc',
		'cParentID'=>$setListAt->getCollectionID(),
		'cThis'=>1,
		'includeAllDescendents'=>0,
		'paginate'=>0,
		'displayAliases'=>1,
		'ctID'=>0,
		'rss'=>'',
		'rssTitle'=>'',
		'rssDescription'=>'',
		'truncateSummaries'=>1,
		'displayFeaturedOnly'=>0,
		'truncateChars'=>128
	));
	$pagelist_block->setCustomtemplate('service_locations');
		}
	
	function install_dp_settings(){
		}
  
	function load_required_models() {
	\Loader::model('attribute/categories/collection');	
		}
		
	public function on_start(){
		$al = AssetList::getInstance();
		$al->register('css', 'lightbox', 'css/lightbox.css', array('version' => '1', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => false), $this);
		$al->register('javascript', 'lightbox', 'js/lightbox.min.js', array('version' => '1', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => false), $this);	
		$al->register('css', 'taginput', 'css/bootstrap-tagsinput.css', array('version' => '1', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => false), $this);
		$al->register('javascript', 'taginput', 'js/bootstrap-tagsinput.js', array('version' => '1', 'position' => Asset::ASSET_POSITION_FOOTER, 'minify' => false, 'combine' => false), $this);
        
        $pkg = Package::getByHandle($this->pkgHandle);    
        SinglePage::add('/dashboard/external_form/contact_form',$pkg);
        
        SinglePage::add('/dashboard/external_form/external_contact_form',$pkg);
	}	
	
		
 			
}