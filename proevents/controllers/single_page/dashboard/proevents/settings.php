<?php  
namespace Concrete\Package\Proevents\Controller\SinglePage\Dashboard\Proevents;

//use \Concrete\Core\Page\PageList as CorePageList;
use \Concrete\Package\Proevents\Helper\Form\DateTimeTime as DateTimeTimeHelper;
use \Concrete\Core\Page\Controller\DashboardPageController;
use \Concrete\Package\Proevents\Helper\Form\Time as TimeHelper;
use \Concrete\Package\Proevents\Helper\Eventify as EventifyHelper;
use \Concrete\Core\Page\PageList as PageList;
use \Concrete\Core\Page\Type\Type as CollectionType;
use Loader;
use View;
use Package;

class Settings extends DashboardPageController
{

    public $helpers = array('html', 'form');

    public function view()
    {
        $db = Loader::db();
        $r = $db->execute("SELECT * FROM btProEventSettings");
        while ($row = $r->fetchrow()) {
            $this->set('themed', $row['themed']);
            $this->set('showHolidays', $row['showHolidays']);
            $this->set('showTooltips', $row['showTooltips']);
            $this->set('tooltipColor', $row['tooltipColor']);
            $this->set('defaultView', $row['defaultView']);
            $this->set('time_formatting', $row['time_formatting']);
            $this->set('search_path', $row['search_path']);
            $this->set('tweets', $row['tweets']);
            $this->set('google', $row['google']);
            $this->set('xml_feeds', isset($row['xml_feeds']) ? explode(':^:', $row['xml_feeds']) : '');
            $this->set('fb_like', $row['fb_like']);
            $this->set('invites', $row['invites']);
            $this->set('ctID', $row['ctID']);
            $this->set('tz_format', $row['tz_format']);
            $this->set('sharethis_key', $row['sharethis_key']);
            $this->set('user_events', $row['user_events']);
        }
        $this->loadPageTypes();

        $pkg = Package::getByHandle('proevents');
        $this->set('timeformat',$pkg->getConfig()->get('formatting.time', false));
        $this->set('datefault',$pkg->getConfig()->get('formatting.date', false));
        $this->set('datespoken',$pkg->getConfig()->get('formatting.datespoken', false));
        $this->set('datepicker',$pkg->getConfig()->get('formatting.datepicker', false));

    }

    function save_settings()
    {
        $feeds = '';
        if ($this->post('xml_feeds')) {
            $feeds = implode(':^:', $this->post('xml_feeds'));
        }
        $args = array(
            'themed' => ($this->post('themed')) ? 'true' : 'false',
            'showHolidays' => $this->post('showHolidays'),
            'showTooltips' => $this->post('showTooltips'),
            'tooltipColor' => $this->post('tooltipColor'),
            'defaultView' => $this->post('defaultView'),
            'time_formatting' => $this->post('time_formatting'),
            'search_path' => $this->post('search_path'),
            'tweets' => $this->post('tweets'),
            'google' => $this->post('google'),
            'fb_like' => $this->post('fb_like'),
            'invites' => $this->post('invites'),
            'ctID' => $this->post('ctID'),
            'xml_feeds' => str_replace('basic', 'full', $feeds),
            'tz_format' => $this->post('tz_format'),
            'sharethis_key' => $this->post('sharethis_key'),
            'user_events' => $this->post('user_events')
        );

        $db = Loader::db();

        $db->EXECUTE("DELETE FROM btProEventSettings");

        $db->insert('btProEventSettings', $args);

        $pkg = Package::getByHandle('proevents');
        $pkg->getConfig()->save('formatting.time', $this->post('timeformat'));
        $pkg->getConfig()->save('formatting.date', $this->post('datefault'));
        $pkg->getConfig()->save('formatting.datespoken', $this->post('datespoken'));
        $pkg->getConfig()->save('formatting.datepicker', $this->post('datepicker'));

        $this->view();
    }

    protected function loadPageTypes()
    {
        $ctArray = CollectionType::getList('');
        $pageTypes = array();
        foreach ($ctArray as $ct) {
            $pageTypes[$ct->getPageTypeID()] = $ct->getPageTypeName();
        }
        $this->set('pageTypes', $pageTypes);
    }
}