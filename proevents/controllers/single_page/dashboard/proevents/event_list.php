<?php  
namespace Concrete\Package\Proevents\Controller\SinglePage\Dashboard\Proevents;

//use \Concrete\Core\Page\PageList as CorePageList;
use \Concrete\Core\Page\Controller\DashboardPageController;
use \Concrete\Core\Page\Page as Page;
use \Concrete\Core\Page\PageList as PageList;
use \Concrete\Core\Page\Type\Type as CollectionType;
use Loader;

class EventList extends DashboardPageController
{

    public $num = 15;

    public $helpers = array('html', 'form');

    public function on_start()
    {
        Loader::model('page_list');
        $this->error = Loader::helper('validation/error');
    }


    public function view()
    {
        $this->loadeventSections();
        $eventList = new PageList();

        $eventList->sortBy('cDateAdded', 'desc');
        if (isset($_GET['cParentID']) && $_GET['cParentID'] > 0) {
            $eventList->filterByParentID($_GET['cParentID']);
        }
        if (empty($_GET['cParentID'])) {
            $sections = $this->get('sections');
            $keys = array_keys($sections);
            $keys[] = -1;
            $eventList->filterByParentID($keys);
        }
        if (!empty($_GET['like'])) {
            $eventList->filterByName($_GET['like']);
        }
        if (!empty($_GET['cat'])) {
            $cat = $_GET['cat'];
            $eventList->filter(false, "ak_event_category = '\n$cat\n'");
        }
        if (!empty($_GET['tag'])) {
            $tag = $_GET['tag'];
            $eventList->filter(false, "ak_event_tag = '\n$tag\n'");
        }
        $eventList->setItemsPerPage($this->num);
        $this->set('eventList', $eventList);
        $this->set('eventResults', $eventList->getResults());
        $this->set('cat_values', $this->getEventCats());
        $this->set('tag_values', $this->getEventTags());

    }

    protected function loadeventSections()
    {
        $eventSectionList = new PageList();
        $eventSectionList->setItemsPerPage($this->num);
        $eventSectionList->filter(false, "ak_event_section = 1");
        $eventSectionList->sortBy('cvName', 'asc');
        $tmpSections = $eventSectionList->get();
        $sections = array();
        foreach ($tmpSections as $_c) {
            $sections[$_c->getCollectionID()] = $_c->getCollectionName();
        }
        $this->set('sections', $sections);
    }

    public function delete_check($cIDd, $name)
    {
        $this->set('remove_name', urldecode($name));
        $this->set('remove_cid', $cIDd);
        $this->view();
    }

    public function delete($cIDd, $name)
    {
        $c = Page::getByID($cIDd);
        $db = Loader::db();
        $db->Execute("DELETE from btProEventDates where eventID = '$cIDd'");
        $c->delete();
        $this->set('message', t('"' . urldecode($name) . '" has been deleted'));
        $this->set('remove_name', '');
        $this->set('remove_cid', '');
        $this->view();
    }

    public function duplicate($cIDd)
    {
        $c = Page::getByID($cIDd);
        $cpID = $c->getCollectionParentID();
        $cp = Page::getByID($cpID);
        $c->duplicate($cp);
        $this->view();
    }

    public function clear_warning()
    {
        $this->set('remove_name', '');
        $this->set('remove_cid', '');
        $this->view();
    }


    public function getEventCats()
    {
        $db = Loader::db();
        $akID = $db->query("SELECT akID FROM AttributeKeys WHERE akHandle = 'event_category'");
        while ($row = $akID->fetchrow()) {
            $akIDc = $row['akID'];
        }
        $akv = $db->execute("SELECT value FROM atSelectOptions WHERE akID = $akIDc");
        while ($row = $akv->fetchrow()) {
            $values[] = $row;
        }
        if (empty($values)) {
            $values = array();
        }
        return $values;
    }


    public function getEventTags()
    {
        $db = Loader::db();
        $akID = $db->query("SELECT akID FROM AttributeKeys WHERE akHandle = 'event_tag'");
        while ($row = $akID->fetchrow()) {
            $akIDc = $row['akID'];
        }
        $akv = $db->execute("SELECT value FROM atSelectOptions WHERE akID = $akIDc");
        while ($row = $akv->fetchrow()) {
            $values[] = $row;
        }
        if (empty($values)) {
            $values = array();
        }
        return $values;
    }


    public function event_added()
    {
        $this->set('message', t('Event added.'));
        $this->view();
    }

    public function event_updated()
    {
        $this->set('message', t('Event updated.'));
        $this->view();
    }


}