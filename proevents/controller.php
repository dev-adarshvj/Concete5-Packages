<?php  
namespace Concrete\Package\Proevents;

use URL;
use Log;
use App;
use Core;
use Route;
use Package;
use Events;
use BlockType;
use SinglePage;
use Page;
use PageList;
use View;
use Loader;
use User;

use \Concrete\Core\Page\Type\Type as CollectionType;
use \Concrete\Core\Page\Type\PublishTarget\Type\Type as PublishTarget;
use \Concrete\Core\Page\Type\PublishTarget\Configuration\ParentPageConfiguration as ParentPageConfiguration;
use \Concrete\Core\Page\Template;
use \Concrete\Core\Page\Type\Composer\Control\Type\Type as PageTypeComposerControlType;

use \Concrete\Core\Attribute\Type as AttributeType;
use \Concrete\Core\Attribute\Key\Category as AttributeKeyCategory;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;
use \Concrete\Attribute\Select\Option as SelectAttributeTypeOption;
use \Concrete\Core\Application\Service\UserInterface\Menu;

use \Concrete\Core\Foundation\Service\Provider as ServiceProvider;

use \Concrete\Package\Proevents\Src\ProEvents\EventItemDates;

class Controller extends Package
{

    protected $pkgHandle = 'proevents';
    protected $appVersionRequired = '5.7.3';
    protected $pkgVersion = '2.3.0';

    public function getPackageDescription()
    {
        return t("A Professional Event Package");
    }

    public function getPackageName()
    {
        return t("ProEvents");
    }

    public function install()
    {

        $pkg = parent::install();

        //install blocks
        BlockType::installBlockTypeFromPackage('pro_event_list', $pkg);

        $this->install_event_attributes($pkg);

        $this->add_se_pages($pkg);

        $this->add_event_page($pkg);

        // install pages
        $iak = CollectionAttributeKey::getByHandle('icon_dashboard');

        $cp = SinglePage::add('/dashboard/proevents', $pkg);
        $cp = Page::getByPath('/dashboard/proevents');
        $cp->update(array('cName' => t('ProEvents'), 'cDescription' => t('Professional event management')));

        $pel = SinglePage::add('/dashboard/proevents/event_list', $pkg);
        $pel = Page::getByPath('/dashboard/proevents/event_list');
        $pel->setAttribute($iak, 'icon-list-alt');

        $an = SinglePage::add('/dashboard/proevents/add_event', $pkg);
        $an = Page::getByPath('/dashboard/proevents/add_event');
        //$an->update(array('cName'=>t('Add/Edit')));
        $an->setAttribute($iak, 'icon-calendar');

        $generated_dates = SinglePage::add('/dashboard/proevents/generated_dates', $pkg);
        $generated_dates = Page::getByPath('/dashboard/proevents/generated_dates');
        $generated_dates->setAttribute($iak, 'icon-list');

        $pes = SinglePage::add('/dashboard/proevents/settings', $pkg);
        $pes = Page::getByPath('/dashboard/proevents/settings');
        $pes->setAttribute($iak, 'icon-wrench');


        $this->setDefaults();

    }

    public function uninstall()
    {
	    /************************************************************************************
		 * SimpleEvents Rollback
		 ************************************************************************************/
	    $sepkg = Package::getByHandle('simpleevents');
	    if($sepkg->pkgID){
        	$pkgID = $sepkg->getPackageID();
	        $db = Loader::db();
	        
	        //update attributes
	        $db->update('AttributeTypes',array('pkgID'=>$pkgID),array('atHandle'=>'multi_date'));
	        $db->update('AttributeKeys',array('pkgID'=>$pkgID),array('akHandle'=>'event_multidate'));
	        
	        //update attribute values
			$db->Execute("DELETE from atSelectOptions where value = ?", array('every other week'));
			$db->Execute("DELETE from atSelectOptions where value = ?", array('yearly'));
	        
	        //update pages
	        $pt = CollectionType::getByHandle('se_post');

	        $ect = CollectionType::getByHandle('pe_post');
	        $ectID = $ect->getPageTypeID();
	        
	        $pl = new PageList();
	        $pl->filter(false,"p.ptID = $ectID");
	        $events = $pl->get();
	        foreach($events as $event){
		        $event->setPageType($pt);
	        }
	    }

        parent::uninstall();
    }

    public function upgrade()
    {

        $db = Loader::db();

        $pkg = Package::getByHandle('proevents');

        parent::upgrade();

    }

    function add_event_page($pkg)
    {

        /*
         * Add new Event template
         */
        $tmplt = Template::getByHandle('right_sidebar');

        /*
         * Add new Event Page Type using new Template
         */

        $seteventAt = Page::getByPath('/event');

        $type = CollectionType::add(
            array(
                'handle' => 'pe_post',
                'name' => 'Event',
                'defaultTemplate' => $tmplt,
                'allowedTemplates' => 'C',
                'templates' => array($tmplt),
                'ptLaunchInComposer' => 1
            ),
            $pkg
        );
        
        //check for SE and convert pages
        $sepkg = Package::getByHandle('simpleevents');
	    if($sepkg->pkgID){
        	//update pages
        	$ct = CollectionType::getByHandle('se_post');
        	$ectID = $ct->getPageTypeID();
		
			$pl = new PageList();
	        $pl->filter(false,"p.ptID = $ectID");
	        $events = $pl->get();
	        foreach($events as $event){
		        $event->setPageType($type);
	        }
	    }

        /*
         * Grab the 'parent_page' Page Type configuration Object
         */
        $pt_target = PublishTarget::getByHandle('parent_page');
        $configuration = new ParentPageConfiguration($pt_target);
        $configuration->setParentPageID($seteventAt->getCollectionID());

        /*
         * Set Event Page Type to use Parent Page Configuration
         */
        $type->setConfiguredPageTypePublishTargetObject($configuration);

        /*
         * Create Event Page Type Form Layout Sets
         */
        $info = $type->addPageTypeComposerFormLayoutSet('Info', 'Basic Event Information');
        $post = $type->addPageTypeComposerFormLayoutSet('Post', 'Your Event Content');
        $dates = $type->addPageTypeComposerFormLayoutSet('Dates', 'Your Event Dates');
        $exclude = $type->addPageTypeComposerFormLayoutSet('Exclude Dates', 'Special Exclude Date');
        $links = $type->addPageTypeComposerFormLayoutSet('Links', 'Event Links');


        /*
         * Create Event Page Type Form Layout Controls
         */
        $core_att_controls = PageTypeComposerControlType::getByHandle('core_page_property');
        $page_att_controls = PageTypeComposerControlType::getByHandle('collection_attribute');
        $block_controls = PageTypeComposerControlType::getByHandle('block');


        /*
         * Create Event Page Type Form Layout Controls for Info
         * ++++++++++++++++++++++
         * Info Tab
         * ++++++++++++++++++++++
         */

        /* Event Title */
        $name = $core_att_controls->getPageTypeComposerControlByIdentifier('name');
        $name->addToPageTypeComposerFormLayoutSet($info);

        /* Event Category */
        $control_id = CollectionAttributeKey::getByHandle('event_category')->getAttributeKeyID();
        $event_category = $page_att_controls->getPageTypeComposerControlByIdentifier($control_id);
        $event_category->addToPageTypeComposerFormLayoutSet($info);

        /* Event Exclude Links */
        $control_id = CollectionAttributeKey::getByHandle('exclude_nav')->getAttributeKeyID();
        $event_exclude = $page_att_controls->getPageTypeComposerControlByIdentifier($control_id);
        $event_exclude->addToPageTypeComposerFormLayoutSet($info);


        /*
         * Create Event Page Type Form Layout Controls for Post
         * ++++++++++++++++++++++
         * Post Tab
         * ++++++++++++++++++++++
         */

        /* Event Description */
        $description = $core_att_controls->getPageTypeComposerControlByIdentifier('description');
        $description->addToPageTypeComposerFormLayoutSet($post);


        /* Event Content */
        $control_id = BlockType::getByHandle('content')->getBlockTypeID();
        $event_content = $block_controls->getPageTypeComposerControlByIdentifier($control_id);
        $event_content->addToPageTypeComposerFormLayoutSet($post);


        /*
         * Create Event Page Type Form Layout Controls for Dates
         * ++++++++++++++++++++++
         * Dates Tab
         * ++++++++++++++++++++++
         */

        /* Event Dates */
        $control_id = CollectionAttributeKey::getByHandle('event_multidate')->getAttributeKeyID();
        $event_multidate = $page_att_controls->getPageTypeComposerControlByIdentifier($control_id);
        $event_multidate->addToPageTypeComposerFormLayoutSet($dates);

        /* Event Thru */
        $control_id = CollectionAttributeKey::getByHandle('event_thru')->getAttributeKeyID();
        $event_thru = $page_att_controls->getPageTypeComposerControlByIdentifier($control_id);
        $event_thru->addToPageTypeComposerFormLayoutSet($dates);

        /* Event All Day */
        $control_id = CollectionAttributeKey::getByHandle('event_allday')->getAttributeKeyID();
        $event_allday = $page_att_controls->getPageTypeComposerControlByIdentifier($control_id);
        $event_allday->addToPageTypeComposerFormLayoutSet($dates);

        /* Event Group Dates */
        $control_id = CollectionAttributeKey::getByHandle('event_grouped')->getAttributeKeyID();
        $event_grouped = $page_att_controls->getPageTypeComposerControlByIdentifier($control_id);
        $event_grouped->addToPageTypeComposerFormLayoutSet($dates);

        /* Event Recur */
        $control_id = CollectionAttributeKey::getByHandle('event_recur')->getAttributeKeyID();
        $event_recur = $page_att_controls->getPageTypeComposerControlByIdentifier($control_id);
        $event_recur->addToPageTypeComposerFormLayoutSet($dates);


        /*
         * Create Event Page Type Form Layout Controls for Exclude Dates
         * ++++++++++++++++++++++
         * Exclude Dates Tab
         * ++++++++++++++++++++++
         */

        /* Event Exclude */
        $control_id = CollectionAttributeKey::getByHandle('event_exclude')->getAttributeKeyID();
        $event_exclude = $page_att_controls->getPageTypeComposerControlByIdentifier($control_id);
        $event_exclude->addToPageTypeComposerFormLayoutSet($exclude);


        /*
         * Create Event Page Type Form Layout Controls for Links
         * ++++++++++++++++++++++
         * Links Tab
         * ++++++++++++++++++++++
         */

        /* Event Links */
        $control_id = CollectionAttributeKey::getByHandle('thumbnail')->getAttributeKeyID();
        $event_thumbnail = $page_att_controls->getPageTypeComposerControlByIdentifier($control_id);
        $event_thumbnail->addToPageTypeComposerFormLayoutSet($links);

        /* Event Location */
        $control_id = CollectionAttributeKey::getByHandle('event_local')->getAttributeKeyID();
        $event_event_local = $page_att_controls->getPageTypeComposerControlByIdentifier($control_id);
        $event_event_local->addToPageTypeComposerFormLayoutSet($links);

        /* Event Address */
        $control_id = CollectionAttributeKey::getByHandle('address')->getAttributeKeyID();
        $event_address = $page_att_controls->getPageTypeComposerControlByIdentifier($control_id);
        $event_address->addToPageTypeComposerFormLayoutSet($links);

        /* Event Contact Name */
        $control_id = CollectionAttributeKey::getByHandle('contact_name')->getAttributeKeyID();
        $event_contact_name = $page_att_controls->getPageTypeComposerControlByIdentifier($control_id);
        $event_contact_name->addToPageTypeComposerFormLayoutSet($links);

        /* Event Contact Email */
        $control_id = CollectionAttributeKey::getByHandle('contact_email')->getAttributeKeyID();
        $event_contact_email = $page_att_controls->getPageTypeComposerControlByIdentifier($control_id);
        $event_contact_email->addToPageTypeComposerFormLayoutSet($links);

        /* Event Tags */
        $control_id = CollectionAttributeKey::getByHandle('event_tag')->getAttributeKeyID();
        $event_thumbnail = $page_att_controls->getPageTypeComposerControlByIdentifier($control_id);
        $event_thumbnail->addToPageTypeComposerFormLayoutSet($links);
        
        
        $db = Loader::db();
        $cocID = $db->getOne("SELECT ptComposerOutputControlID FROM PageTypeComposerOutputControls WHERE pTemplateID = ? AND ptID = ?",array($tmplt->getPageTemplateID(),$type->getPageTypeID()));
        
        $this->install_pe_page_defaults($pkg,$cocID);

    }

    function install_event_attributes($pkg)
    {

        $eaku = AttributeKeyCategory::getByHandle('collection');
        $eaku->setAllowAttributeSets(AttributeKeyCategory::ASET_ALLOW_SINGLE);
        $evset = $eaku->addSet('proevent', t('Pro Events'), $pkg);
        $evsbt = $eaku->addSet('proevent_booking_attributes', t('Pro Events Booking Attributes'), $pkg);
        $evsot = $eaku->addSet('proevent_additional_attributes', t('Pro Events Additional Attributes'), $pkg);

        $euku = AttributeKeyCategory::getByHandle('user');
        $euku->setAllowAttributeSets(AttributeKeyCategory::ASET_ALLOW_SINGLE);
        $uset = $euku->addSet('user_set', t('Events Package'), $pkg);


        $multidateAttribute = AttributeType::getByHandle('multi_date');
        if (!is_object($multidateAttribute) || !intval($multidateAttribute->getAttributeTypeID())) {
            $multidateAttribute = AttributeType::add('multi_date', t('Multi Date'), $pkg);
            $eaku->associateAttributeKeyType(AttributeType::getByHandle('multi_date'));
        }else{
	        $pkgID = $pkg->getPackageID();
	        $db = Loader::db();
	        $db->update('AttributeTypes',array('pkgID'=>$pkgID),array('atHandle'=>'multi_date'));
	        $db->update('AttributeKeys',array('pkgID'=>$pkgID),array('akHandle'=>'event_multidate'));
        }

        $event_category = AttributeType::getByHandle('event_category');
        if (!is_object($event_category) || !intval($event_category->getAttributeTypeID())) {
            $event_category = AttributeType::add('event_category', t('Event Category'), $pkg);
            $eaku->associateAttributeKeyType(AttributeType::getByHandle('event_category'));
        }

        $price = AttributeType::getByHandle('price');
        if (!is_object($price) || !intval($price->getAttributeTypeID())) {
            $price = AttributeType::add('price', t('Price'), $pkg);
        }

        $price = AttributeType::getByHandle('price');
        $timen = AttributeType::getByHandle('time');
        $multidateAttribute = AttributeType::getByHandle('multi_date');
        $event_category = AttributeType::getByHandle('event_category');


        $eventmulti = CollectionAttributeKey::getByHandle('event_multidate');
        if (!is_object($eventmulti)) {
            CollectionAttributeKey::add(
                $multidateAttribute,
                array(
                    'akHandle' => 'event_multidate',
                    'akName' => t('Event Dates'),
                    'akIsSearchable' => '1',
                    'akIsSearchableIndexed' => '1',
                    'akDateDisplayMode' => 'date_time_time'
                ),
                $pkg
            )->setAttributeSet($evset);
        }

        $eventexclude = CollectionAttributeKey::getByHandle('event_exclude');
        if (!is_object($eventexclude)) {
            CollectionAttributeKey::add(
                $multidateAttribute,
                array(
                    'akHandle' => 'event_exclude',
                    'akName' => t('Event Exclude Dates'),
                    'akIsSearchable' => '1',
                    'akIsSearchableIndexed' => '1',
                    'akDateDisplayMode' => 'date'
                ),
                $pkg
            )->setAttributeSet($evset);
        }

        $checkn = AttributeType::getByHandle('boolean');
        $eventsec = CollectionAttributeKey::getByHandle('event_section');
        if (!is_object($eventsec)) {
            CollectionAttributeKey::add(
                $checkn,
                array(
                    'akHandle' => 'event_section',
                    'akName' => t('Calender'),
                    'akIsSearchable' => 1,
                    'akIsSearchableIndexed' => 1
                ),
                $pkg
            )->setAttributeSet($evset);
        }


        $eventall = CollectionAttributeKey::getByHandle('event_allday');
        if (!is_object($eventall)) {
            CollectionAttributeKey::add(
                $checkn,
                array(
                    'akHandle' => 'event_allday',
                    'akName' => t('All Day Event?'),
                ),
                $pkg
            )->setAttributeSet($evset);
        }

        $eventgrouped = CollectionAttributeKey::getByHandle('event_grouped');
        if (!is_object($eventgrouped)) {
            CollectionAttributeKey::add(
                $checkn,
                array(
                    'akHandle' => 'event_grouped',
                    'akName' => t('Group Dates?'),
                ),
                $pkg
            )->setAttributeSet($evset);
        }

        $eventcat = CollectionAttributeKey::getByHandle('event_category');
        if (!is_object($eventcat)) {
            CollectionAttributeKey::add(
                $event_category,
                array(
                    'akHandle' => 'event_category',
                    'akName' => t('Event Category'),
                ),
                $pkg
            )->setAttributeSet($evset);
        }

        $pulln = AttributeType::getByHandle('select');
        $eventtag = CollectionAttributeKey::getByHandle('event_tag');
        if (!is_object($eventtag)) {
            CollectionAttributeKey::add(
                $pulln,
                array(
                    'akHandle' => 'event_tag',
                    'akName' => t('Event Tags'),
                    'akIsSearchable' => '1',
                    'akIsSearchableIndexed' => '1',
                    'akSelectAllowMultipleValues' => true,
                    'akSelectAllowOtherValues' => true,
                ),
                $pkg
            )->setAttributeSet($evset);
        }

        $imagen = AttributeType::getByHandle('image_file');
        $eventthum = CollectionAttributeKey::getByHandle('thumbnail');
        if (!is_object($eventthum)) {
            CollectionAttributeKey::add(
                $imagen,
                array(
                    'akHandle' => 'thumbnail',
                    'akName' => t('Thumbnail Image'),
                ),
                $pkg
            );
        }


        $textn = AttributeType::getByHandle('text');
        $eventurl = CollectionAttributeKey::getByHandle('event_local');
        if (!is_object($eventurl)) {
            CollectionAttributeKey::add(
                $textn,
                array(
                    'akHandle' => 'event_local',
                    'akName' => t('Event Location'),
                ),
                $pkg
            )->setAttributeSet($evset);
        }

        $address = CollectionAttributeKey::getByHandle('address');
        if (!is_object($address)) {
            CollectionAttributeKey::add(
                $textn,
                array(
                    'akHandle' => 'address',
                    'akName' => t('Address'),
                ),
                $pkg
            )->setAttributeSet($evset);
        }

        $contact = CollectionAttributeKey::getByHandle('contact_name');
        if (!is_object($contact)) {
            CollectionAttributeKey::add(
                $textn,
                array(
                    'akHandle' => 'contact_name',
                    'akName' => t('Contact Name'),
                ),
                $pkg
            )->setAttributeSet($uset);
        }

        $conemail = CollectionAttributeKey::getByHandle('contact_email');
        if (!is_object($conemail)) {
            CollectionAttributeKey::add(
                $textn,
                array(
                    'akHandle' => 'contact_email',
                    'akName' => t('Contact Email'),
                ),
                $pkg
            )->setAttributeSet($uset);
        }

        $daten = AttributeType::getByHandle('date');
        $eventthru = CollectionAttributeKey::getByHandle('event_thru');
        if (!is_object($eventthru)) {
            CollectionAttributeKey::add(
                $daten,
                array(
                    'akHandle' => 'event_thru',
                    'akName' => t('End Date'),
                    'akIsSearchable' => '1',
                    'akIsSearchableIndexed' => '1',
                    'akDateDisplayMode' => 'date',
                ),
                $pkg
            )->setAttributeSet($evset);
        }


        $eventrecur = CollectionAttributeKey::getByHandle('event_recur');
        if (!is_object($eventrecur)) {
            $eventrecur = CollectionAttributeKey::add(
                $pulln,
                array(
                    'akHandle' => 'event_recur',
                    'akName' => t('Recurring'),
                    'akIsSearchable' => '1',
                    'akIsSearchableIndexed' => '1'
                ),
                $pkg
            )->setAttributeSet($evset);
            $eventrecur = CollectionAttributeKey::getByHandle('event_recur');
            SelectAttributeTypeOption::add($eventrecur, t('daily'));
            SelectAttributeTypeOption::add($eventrecur, t('weekly'));
            SelectAttributeTypeOption::add($eventrecur, t('every other week'));
            SelectAttributeTypeOption::add($eventrecur, t('monthly'));
            SelectAttributeTypeOption::add($eventrecur, t('yearly'));
            //SelectAttributeTypeOption::add($ak,'twice per month');
        }else{
	        $eventrecur = CollectionAttributeKey::getByHandle('event_recur');
	        SelectAttributeTypeOption::add($eventrecur, t('every other week'));
	        SelectAttributeTypeOption::add($eventrecur, t('yearly'));
        }

        $event_qty = CollectionAttributeKey::getByHandle('event_qty');
        if (!is_object($event_qty)) {
            CollectionAttributeKey::add(
                $textn,
                array(
                    'akHandle' => 'event_qty',
                    'akName' => t('Event Qty'),
                ),
                $pkg
            )->setAttributeSet($evsbt);
        }

        $event_price = CollectionAttributeKey::getByHandle('event_price');
        if (!is_object($event_price)) {
            CollectionAttributeKey::add(
                $price,
                array(
                    'akHandle' => 'event_price',
                    'akName' => t('Event Price'),
                ),
                $pkg
            )->setAttributeSet($evsbt);
        }
    }

    function add_se_pages($pkg)
    {

        $db = Loader::db();

        $pageType = CollectionType::getByHandle('full');
        if (!is_object($pageType) || $pageType == false) {
            $pageType = CollectionType::getByHandle('full_width');
        }
        $pageType = CollectionType::getByHandle('right_sidebar');
        if (!is_object($pageType) || $pageType == false) {
            $pageType = CollectionType::getByHandle('left_sidebar');
        }


        $pageeventParent = Page::getByID(HOME_CID);

        $seteventAt = Page::getByPath('/event');

        if (!is_object($seteventAt) || !$seteventAt->cID) {
            $pageeventParent->add(
                $pageType,
                array('cName' => 'Events', 'cHandle' => 'event', 'pkgID' => $pkg->getPackageID())
            );
        }

        $seteventAt = Page::getByPath('/event');
        $seteventAt->setAttribute('event_section', 1);

        $cIDn = $seteventAt->getCollectionID();

        $block = $seteventAt->getBlocks('Main');
        foreach ($block as $b) {
            $b->delete();
        }

        $bt = BlockType::getByHandle('pro_event_list');

        $data = array(
            'num' => '5',
            'isPaged' => '1',
            'nonelistmsg' => 'There are no events at this time',
            'ordering' => 'ASC',
            'showfeed' => '1',
            'rssTitle' => 'Latest event',
            'rssDescription' => 'Our latest event feed',
            'truncateSummaries' => '1',
            'truncateChars' => '128',
            'ctID' => 'All Categories',
            'sctID' => 'All Sections'
        );

        $b = $seteventAt->addBlock($bt, 'Main', $data);
        $b->setCustomTemplate('templates/calendar_responsive');

        $seteventAt->reindex();

    }
    
    
    public function install_pe_page_defaults($pkg,$cocID)
    {
        $pageType = CollectionType::getByHandle('pe_post');
        $ctTemplate = $pageType->getPageTypeDefaultPageTemplateObject();
        $blogPostCollectionTypeMT = $pageType->getPageTypePageTemplateDefaultPageObject($ctTemplate);

        $ctID = $pageType->getPageTypeID();
        $bt = BlockType::getByHandle('pro_event_list');

        $cIDn = Page::getByPath('/events')->getCollectionID();
        
        
        //add composer controll output to pb_post defaults
        $bt = BlockType::getByHandle('core_page_type_composer_control_output');
        $data = array(
	      'ptComposerOutputControlID' => $cocID
        );
        $blogPostCollectionTypeMT->addBlock($bt, 'Main', $data);
        

        //install guestbook to page_type template
        $guestBookBT = BlockType::getByHandle('core_conversation');
        $guestbookArray = array();
        $guestbookArray['attachmentsEnabled'] = 0;
        $guestbookArray['title'] = t('Please add a comment');
        $guestbookArray['itemsPerPage'] = 14;
        $guestbookArray['enablePosting'] = 1;
        $guestbookArray['paginate'] = 1;
        $guestbookArray['displayMode'] = 'threaded';
        $blogPostCollectionTypeMT->addBlock($guestBookBT, 'Blog Post More', $guestbookArray);

    }
    
    

    function setDefaults()
    {

        $pe_post = CollectionType::getByHandle('pe_post');

        $args = array(
            'themed' => false,
            'showHolidays' => true,
            'showTooltips' => true,
            'tooltipColor' => 'dark',
            'defaultView' => 'month',
            'time_formatting' => 'us',
            'tz_format' => '',
            'search_path' => '',
            'tweets' => true,
            'google' => true,
            'fb_like' => true,
            'invites' => true,
            'user_events' => false,
            'ctID' => $pe_post->ctID,
            'xml_feeds' => false,
            'sharethis_key' => false
        );

        $db = Loader::db();

        $db->Execute("DELETE FROM btProEventSettings");

        $db->insert('btProEventSettings', $args);

        $pkg = Package::getByHandle('proevents');
        $pkg->getConfig()->save('formatting.time', '12');
        $pkg->getConfig()->save('formatting.date', 'Y-m-d');
        $pkg->getConfig()->save('formatting.datespoken', 'D M jS');
        $pkg->getConfig()->save('formatting.datepicker', 'yy-mm-dd');

    }

    public function registerHelpers()
    {
        // path:  /packages/my_package/src/Buttress/MyPackage/Request/RequestServiceProvider
        // namespace: \Buttress\MyPackage\Request\RequestServiceProvider
        Core::bind(
            'helper/eventify',
            '\Concrete\Package\Proevents\Controller\Helpers\Eventify'
        );
        Core::bind(
            'helper/form/time',
            '\Concrete\Package\Proevents\Controller\Helpers\Form\Time'
        );
        Core::bind(
            'helper/form/datetimetime',
            '\Concrete\Package\Proevents\Controller\Helpers\Form\Datetimetime'
        );

    }

    public function registerRoutes()
    {
        /*
         *  Registering EventList Calendar AJAX Views
         */
        Route::register(
            '/proevents/routes/calendar_responsive',
            '\Concrete\Package\Proevents\Controller\Ajax\ProEventList\CalendarResponsive::view'
        );
        Route::register(
            '/proevents/routes/calendar_jquery',
            '\Concrete\Package\Proevents\Controller\Ajax\ProEventList\CalendarJquery::view'
        );
        Route::register(
            '/proevents/routes/calendar_dynamic',
            '\Concrete\Package\Proevents\Controller\Ajax\ProEventList\CalendarDynamic::view'
        );
        Route::register(
            '/proevents/routes/calendar_small',
            '\Concrete\Package\Proevents\Controller\Ajax\ProEventList\CalendarSmall::view'
        );
        Route::register(
            '/proevents/routes/calendar_small_array',
            '\Concrete\Package\Proevents\Controller\Ajax\ProEventList\CalendarSmallArray::view'
        );


        /*
         *  Registering EventList List AJAX Views
         */
        Route::register(
            '/proevents/routes/list_ajax',
            '\Concrete\Package\Proevents\Controller\Ajax\ProEventList\ListAjax::view'
        );


        /*
         *  Registering EventList List XML Views
         */
        Route::register(
            '/proevents/routes/rss',
            '\Concrete\Package\Proevents\Controller\Tools\ProEventList\Rss::view'
        );
        Route::register(
            '/proevents/routes/ical',
            '\Concrete\Package\Proevents\Controller\Tools\ProEventList\Ical::view'
        );

        /*
         *  Registering Tools Files
         */
        Route::register(
            '/proevents/tools/edit_generated_event',
            '\Concrete\Package\Proevents\Controller\Tools\GeneratedEvent::edit'
        );
        Route::register('/proevents/tools/multidate', '\Concrete\Package\Proevents\Controller\Tools\Multidate::render');
        Route::register(
            '/proevents/tools/excludedate',
            '\Concrete\Package\Proevents\Controller\Tools\Excludedate::render'
        );
        Route::register('/proevents/tools/invite', '\Concrete\Package\Proevents\Controller\Tools\Invite::render');
        Route::register(
            '/proevents/tools/invite_validate',
            '\Concrete\Package\Proevents\Controller\Tools\Invite::validate'
        );
        Route::register('/proevents/tools/save_event', '\Concrete\Package\Proevents\Controller\Tools\SaveEvent::save');
        Route::register('/proevents/tools/add_event', '\Concrete\Package\Proevents\Controller\Tools\AddEvent::render');
        Route::register('/proevents/tools/post_event', '\Concrete\Package\Proevents\Controller\Tools\AddEvent::save');
        Route::register('/proevents/tools/menu_item', '\Concrete\Package\Proevents\Controller\HeaderMenu\Controller');
        Route::register('/proevents/tools/color_category', '\Concrete\Package\Proevents\Controller\Tools\ColorCategory::render');
    }

    public function registerEvents()
    {
        Events::addListener(
            'on_page_type_save_composer_form',
            function ($event) {
                $page = $event->getPageObject();
                $ctHandle = $page->getCollectionTypeHandle();
                if ($ctHandle == 'pe_post') {
                    $blocks = $page->getBlocks('Main');
                    foreach ($blocks as $b) {
                        if ($b->getBlockTypeHandle() == 'content') {
                            $b->setCustomTemplate('event_post');
                        }
                    }
                    $event_item = new EventItemDates($page, true);
                }
            }
        );

        Events::addListener(
            'on_page_delete',
            function ($event) {
                $page = $event->getPageObject();
                $eventID = $page->getCollectionID();
                $db = Loader::db();
                $db->Execute("DELETE from btProEventDates where eventID = ?", array($eventID));
            }
        );


        /**
         * Listen for page render and add header nav
         */
        $obj = $this;
        Events::addListener(
            'on_before_render',
            function() use ($obj) {
                $obj->registerNav();
            });
    }

    public function on_start()
    {
        $this->registerHelpers();
        $this->registerRoutes();
        $this->registerEvents();
    }

    public function registerNav()
    {
        $request = \Request::getInstance();
        $u = new User();
        if($u->isLoggedIn()){
            if ($request->get('eID') > 0) {
                $title = t('Edit');
                $icon = 'calendar';
            } else {
                $title = t('Create');
                $icon = 'calendar-o';
            }

            $ihm = \Core::make('helper/concrete/ui/menu');
            $ihm->addPageHeaderMenuItem('proevents', 'proevents',
                array(
                    'icon' => $icon,
                    'label' => $title.t(' Event'),
                    'position' => 'right',
                    'href' => URL::to('/proevents/tools/add_event').'?eID=' . $request->get('eID'),
                    'linkAttributes' => array(
                        'id' => 'page-edit-nav-proevents',
                        'dialog-title' => t('Create Event'),
                        'dialog-on-open' => "",
                        'dialog-on-close' => "location.reload();",
                        'dialog-width' => '700',
                        'dialog-height' => "500",
                        'dialog-modal' => "false",
                        'class' => 'dialog-launch'
                    )
                )
            );
        }
    }

}