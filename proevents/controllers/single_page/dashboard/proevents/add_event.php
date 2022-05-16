<?php  
namespace Concrete\Package\Proevents\Controller\SinglePage\Dashboard\Proevents;

use \Concrete\Core\Page\Controller\DashboardPageController;
use \Concrete\Package\Proevents\Src\ProEvents\EventItemDates;
use \Concrete\Package\Proevents\Src\ProEvents\Event;
use \Concrete\Core\Page\PageList as PageList;
use \Concrete\Core\Page\Type\Type as CollectionType;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;
use Carbon\Carbon;
use Loader;

class AddEvent extends DashboardPageController
{
    public $helpers = array('html', 'form');

    public function on_start()
    {
        $this->error = Loader::helper('validation/error');
        $eventify = new \Concrete\Package\Proevents\Controller\Helpers\Eventify;
        $this->set('eventify', $eventify);
        
        $html = Loader::helper('html');

        $this->addHeaderItem($html->css('app.css'));
        $this->addHeaderItem($html->javascript('jquery.js'));
        $this->addFooterItem($html->javascript('jquery-ui.js'));
        $this->addHeaderItem($html->css('jquery-ui.css'));
        
        $this->requireAsset('redactor');
        $this->requireAsset('core/file-manager');
    }


    public function view()
    {   
        $this->setupForm();
        $this->loadeventSections();
        $eventList = new PageList();
        $eventList->sortBy('cDateAdded', 'desc');
        if (isset($_GET['cParentID']) && $_GET['cParentID'] > 0) {
            $eventList->filterByParentID($_GET['cParentID']);
        } else {
            $sections = $this->get('sections');
            $keys = array_keys($sections);
            $keys[] = -1;
            $eventList->filterByParentID($keys);
        }
    }


    protected function loadeventSections()
    {
        $eventSectionList = new PageList();
        //$eventSectionList->filterByEventSection(1);
        $eventSectionList->filter(false, "ak_event_section = 1");
        $eventSectionList->sortBy('cvName', 'asc');

        $tmpSections = $eventSectionList->get();

        $sections = array();
        foreach ($tmpSections as $_c) {
            $sections[$_c->getCollectionID()] = $_c->getCollectionName();
        }

        $this->set('sections', $sections);
    }


    public function edit($cID)
    {
        $this->setupForm();
        $event = Event::getByID($cID);
        //var_dump($this->post());
        //exit;
        if ($this->isPost()) {
            $this->validate();
            if (!$this->error->has()) {
                $p = Event::getByID($this->post('eventID'));

                $parent = Event::getByID($this->post('cParentID'));
                $ct = CollectionType::getByHandle('pe_post');
                $dates_ak = CollectionAttributeKey::getByHandle('event_multidate');
                $dates_akID = $dates_ak->akID;
                $request = \Request::getInstance();

                $dates = $request->get('akID');
                $dates = $dates[$dates_akID];

                $start_date = date('Y-m-d', strtotime($dates['1']['date_dt']));

                $data = array(
                    'ctID' => $ct->getPageTypeID(),
                    'cDescription' => $this->post('eventDescription'),
                    'cName' => $this->post('eventTitle'),
                    'cDatePublic' => $start_date
                );
                $p->update($data);
                if ($p->getCollectionParentID() != $parent->getCollectionID()) {
                    $p->move($parent);
                }
                Event::saveData($p);

                $event_item = new EventItemDates($p, true);

                $this->redirect('/dashboard/proevents/event_list/', 'event_updated');
            }
        }

        $sections = $this->get('sections');
        if (in_array($event->getCollectionParentID(), array_keys($sections))) {
            $this->set('event', $event);
        } else {
            $this->redirect('/dashboard/proevents/add_event/');
        }
    }

    protected function setupForm()
    {
        $this->loadeventSections();
        Loader::model("collection_types");
        $ctArray = CollectionType::getList('');
        $pageTypes = array();
        foreach ($ctArray as $ct) {
            $pageTypes[$ct->getPageTypeID()] = $ct->getPageTypeName();
        }
        $this->set('pageTypes', $pageTypes);
    }

    public function add()
    {
        $request = \Request::getInstance();
        $this->setupForm();
        if ($this->isPost()) {
            $this->validate();
            if (!$this->error->has()) {
                $parent = Event::getByID($this->post('cParentID'));
                $ct = CollectionType::getByHandle('pe_post');
                $dates_ak = CollectionAttributeKey::getByHandle('event_multidate');
                $dates_akID = $dates_ak->akID;
                $dates = $request->get('akID');
                $dates = $dates[$dates_akID];
                $start_date = date('Y-m-d', strtotime($dates['1']['date_dt']));
                $data = array(
                    'cName' => $this->post('eventTitle'),
                    'cDescription' => $this->post('eventDescription'),
                    'cDatePublic' => $start_date
                );

                $p = $parent->add($ct, $data);

                //save the other atts
                Event::saveData($p);

                //process generated dates
                $event_item = new EventItemDates($p, true);

                $this->redirect('/dashboard/proevents/event_list/', 'event_added');
            }
        }
    }


    protected function validate()
    {
        $vt = Loader::helper('validation/strings');
        $vn = Loader::Helper('validation/numbers');
        $dt = Loader::helper("form/date_time");
        $dth = Loader::helper('form/datetimetime');
        $request = \Request::getInstance();

        if (!$vn->integer($this->post('cParentID'))) {
            $this->error->add(t('You must choose a parent page for this event entry.'));
        }

        if (!$vt->notempty($this->post('eventTitle'))) {
            $this->error->add(t('Title is required'));
        }

        $akdte = CollectionAttributeKey::getByHandle('event_thru');
        $akdteKey = $akdte->getAttributeKeyID();
        $akrc = CollectionAttributeKey::getByHandle('event_recur');
        $akrcKey = $akrc->getAttributeKeyID();
        $akct = CollectionAttributeKey::getByHandle('event_category');
        $ctKey = $akct->getAttributeKeyID();
        foreach ($this->post(akID) as $key => $value) {
            if ($key == $ctKey) {
                foreach ($value as $type => $values) {
                    if ($type == 'atSelectNewOption') {
                        foreach ($values as $cat => $valued) {
                            if ($valued == '') {
                                $this->error->add(t('Categories must have a value'));
                            }
                        }
                    }
                }
            } elseif ($key == $akrcKey) {
                foreach ($value as $type => $values) {
                    if ($type == 'atSelectOptionID') {
                        foreach ($values as $rec => $valued) {
                            if ($valued != '') {
                                $db = Loader::db();
                                $recur = $db->getone("SELECT value FROM atSelectOptions WHERE ID = $valued");
                            }
                        }
                    }
                }
            } elseif ($key == $akdteKey) {
                foreach ($value as $type => $values) {
                    $endDate = $values;

                }
            }
        }

        $dates_ak = CollectionAttributeKey::getByHandle('event_multidate');
        $dates_akID = $dates_ak->akID;
        $dates = $_POST['akID'][$dates_akID];

        $date_count = count($dates);
        if ($date_count == 1) {
            $this->error->add(t('You must have at least one date.'));
        }

        list($year, $month, $day) = explode('-', $dth->getReformattedDate($dates['1']['date_dt']));
        $eventDateStart = array(sprintf("%02s", $month), sprintf("%02s", $day), $year);
        $stdc = new Carbon($eventDateStart[2].'-'.$eventDateStart[0].'-'.$eventDateStart[1]);

        list($year, $month, $day) = explode('-', $dth->getReformattedDate($dates[(count($dates) - 1)]['date_dt']));
        $eventDateDataEnd = array(sprintf("%02s", $month), sprintf("%02s", $day), $year);
        $endc = new Carbon($eventDateDataEnd[2].'-'.$eventDateDataEnd[0].'-'.$eventDateDataEnd[1]);

        $eVal = $request->get('akID');
        $eVal = $eVal[$akdteKey]['value'];
        list($year, $month, $day) = explode('-', $dth->getReformattedDate($eVal));
        $endDateData = array(sprintf("%02s", $month), sprintf("%02s", $day), $year);
        $rdc = new Carbon($endDateData[2].'-'.$endDateData[0].'-'.$endDateData[1]);

        $diff = $endc->diffInDays($stdc);

        if ($date_count >= 2) {

            $rdiff = $endc->diffInDays($rdc,false);

            if ($rdiff < 0 && $recur) {
                $this->error->add(
                    t(
                        'Your Recurring End Date value may not be earlier than or equal to the last date in your date set when the "Recuring" option is set'
                    )
                );
            }
        }

        if ($recur == 'daily' && $date_count > 1 && $diff > 0) {
            $this->error->add(t('Date Set\'s of more than one day may only recur by week or month.'));
        }

        if ($recur == 'weekly' && $diff >= 7) {
            $this->error->add(t('You may not have a Date Set span more than 7 days when recurring weekly.'));
        }

        if ($recur == 'monthly' && $diff >= 29) {
            $this->error->add(t('You may not have a Date Set span more than 29 days when recurring Monthly.'));
        }

    }

}