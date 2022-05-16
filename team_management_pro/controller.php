<?php
namespace Concrete\Package\TeamManagementPro;

use \Concrete\Core\Attribute\Type as AttributeType;
use \Concrete\Core\Attribute\Key\Category as AttributeKeyCategory;
use CollectionAttributeKey;
use AttributeSet;
use Package;
use BlockType;
use SinglePage;
use Loader;
use Config;
use PageType;
use PageTemplate;
use Page;
use Exception;


defined('C5_EXECUTE') or die(_("Access Denied."));

class Controller extends Package {

	 protected $pkgHandle = 'team_management_pro';
	 protected $appVersionRequired = '5.7.0';
	 protected $pkgVersion = '0.9.2';

	 public function getPackageDescription() {
	 	 return t("To Manage Team");
	 }

	public function getPackageName() {
	    return t("Team Management Pro");
	}

	public function uninstall(){

	parent::uninstall();
	}

	public function install() {
	$pkg = parent::install();
	$this->install_dp_singlepages($pkg);
	$this->install_dp_attributes($pkg);
	$this->install_dp_pages($pkg);
	}


	function install_dp_singlepages($pkg){
		if(Page::getByPath("/dashboard/team_management_pro")){
		SinglePage::add("/dashboard/team_management_pro", $pkg);
		}
	if(Page::getByPath("/dashboard/team_management_pro/team_list")){
		SinglePage::add("/dashboard/team_management_pro/team_list", $pkg);
	}
	if(Page::getByPath("/dashboard/team_management_pro/add_edit")){
		SinglePage::add("/dashboard/team_management_pro/add_edit", $pkg);
	}
	if(Page::getByPath("/dashboard/team_management_pro/team_settings")){
		SinglePage::add("/dashboard/team_management_pro/team_settings", $pkg);
	}
	}

	function install_dp_attributes($pkg) {

	$checkn = AttributeType::getByHandle('boolean');


	$eaku = AttributeKeyCategory::getByHandle('collection');
	$eaku->setAllowAttributeSets(AttributeKeyCategory::ASET_ALLOW_SINGLE);
	$evset = $eaku->addSet('team_management', t('Team management'),$pkg);
	Config::save('concrete.team_management_attribute_set_id', $evset->getAttributeSetID());
	$image_file = AttributeType::getByHandle('image_file');
	$textarea = AttributeType::getByHandle('textarea');
	$team_thumbnail=CollectionAttributeKey::getByHandle('team_thumbnail');
	if (!is_object($team_thumbnail)) {
	$team_thumbnail=CollectionAttributeKey::add($image_file,
	array('akHandle'=>'team_thumbnail','akName'=>'Team Thumbnail'),$pkg)->setAttributeSet($evset);
	}


	$team_content=CollectionAttributeKey::getByHandle('team_content');
	if ( !is_object($team_content) ) {
		 $team_content=CollectionAttributeKey::add($textarea,
		 array('akHandle'=>'team_content',
		 'akName'=>'Team Content',
		 'akIsSearchableIndexed'=>'1','akIsSearchable'=>'1','akTextareaDisplayMode'=>'rich_text_advanced'),$pkg)->setAttributeSet($evset);
		}


	$team_management_section=CollectionAttributeKey::getByHandle('team_management_section');
	if( !is_object($team_management_section) ) {
	CollectionAttributeKey::add($checkn,
	array('akHandle' => 'team_management_section',
	'akName' => t('Team Management Section'),
	'akIsSearchable' => 1,
	'akIsSearchableIndexed' => 1
	),$pkg);
	}

	}

	function install_dp_pages($pkg) {

$pagetype = PageType::getByHandle('team_list');
		if (!is_object($pagetype) || $pagetype==false) {
	  		$blogPageType = array('handle' => 'team_list', 'name' => t('Team List'));
      		PageType::add($blogPageType, $pkg);
			}
	$setListAt = Page::getByPath('/team_management_list');
	if(!is_object($setListAt) || $setListAt->getCollectionID()==null){
	$pageeventParent = Page::getByID(HOME_CID);
	$setListAt = $pageeventParent->add($pageType, array('cName' => 'Team Management List', 'cHandle' => 'team_management_list', 'pkgID'=>$pkg->getPackageID()));
	$setListAt->setAttribute('team_management_section',1);
	}else{
	$setListAt->setAttribute('team_management_section',1);
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
	$pagelist_block->setCustomtemplate('team_management');
	$pageType= PageType::getByHandle("team_details");
	if(!is_object($pageType) || $pageType==false){
	$PageTypedetails=array("handle" => "team_details",   "name" => "Team Details","ctIcon"=>t("template3.png"));
	PageType::add($PageTypedetails, $pkg);
	}
}

}
