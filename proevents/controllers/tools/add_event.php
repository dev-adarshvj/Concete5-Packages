<?php  
namespace Concrete\Package\Proevents\Controller\Tools;

use Log;
use Loader;
use Page;
use View;
use Block;
use \Concrete\Core\Controller\Controller as RouteController;
use \Concrete\Package\Proevents\Src\ProEvents\Event as Event;
use \Concrete\Package\Proevents\Src\ProEvents\EventItemDates as EventItemDates;
use \Concrete\Core\Page\PageList as PageList;
use \Concrete\Core\Page\Type\Type as CollectionType;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;
use Carbon\Carbon;

class AddEvent extends RouteController
{

    /**
     * render Add Event dialog
     */
    public function render()
    {
        $request = \Request::getInstance();

        $eventSectionList = new PageList();
        $eventSectionList->filter(false, "ak_event_section = 1");
        $eventSectionList->sortBy('cvName', 'asc');

        $tmpSections = $eventSectionList->get();

        $sections = array();
        foreach ($tmpSections as $_c) {
            $sections[$_c->getCollectionID()] = $_c->getCollectionName();
        }

        if ($request->get('eID')) {
            $eventify = new \Concrete\Package\Proevents\Controller\Helpers\Eventify;
            $event =  $eventify->getEvent($request->get('eID'));
            $this->event = Event::getByID($event['eventID']);
        }


        if (is_object($this->event)) {
            $eventTitle = $this->event->getCollectionName();
            $eventDescription = $this->event->getCollectionDescription();
            $eventDate = $this->event->getCollectionDatePublic();
            $cParentID = $this->event->getCollectionParentID();
            $ctID = $this->event->getCollectionTypeID();
            $eventBody = '';
            $eb = $this->event->getBlocks('Main');
            foreach ($eb as $b) {
                if ($b->getBlockTypeHandle() == 'content') {
                    $eventBody = $b->getInstance()->getContent();
                }
            }
            echo "<div class=\"event_warning alert alert-success\"><span class=\"tooltip icon edit\"></span> " . t(
                    'You are now editing'
                ) . " <b><u>$eventTitle</u></b></div>";
            $task = 'edit';
            $buttonText = t('Update Event');
            $title = 'Update';
        } else {
            $task = 'add';
            $buttonText = t('Add Event');
            $title = 'Add';
        }

        $eventify = new \Concrete\Package\Proevents\Controller\Helpers\Eventify;
        $settings = $eventify->getSettings();

        Loader::PackageElement(
            'tools/add_event',
            'proevents',
            array(
                'event' => $this->event,
                'eventTitle'=>$eventTitle,
                'eventDescription'=>$eventDescription,
                'eventBody'=>$eventBody,
                'sections' => $sections,
                'buttonText'=>$buttonText
            )
        );
    }

    /**
     * Save event
     */
    public function save()
    {
        $request = \Request::getInstance();
        $error = Loader::helper('validation/error');
        $error = $this->validate($error);

        if (!$error->has()) {
            $parent = Event::getByID($request->get('cParentID'));
            $ct = CollectionType::getByHandle('pe_post');
            $dates_ak = CollectionAttributeKey::getByHandle('event_multidate');
            $dates_akID = $dates_ak->akID;

            $dates = $request->get('akID');
            $dates = $dates[$dates_akID];

            $start_date = date('Y-m-d',strtotime($dates['1']['date_dt']));

            $data = array('cName' => str_replace('\\','',$request->get('eventTitle')), 'cDescription' => str_replace('\\','',$request->get('eventDescription')), 'cDatePublic' => $start_date);
            if($request->get('eventID')){
                $p = Event::getByID($request->get('eventID'));
                $p->update($data);
                if ($p->getCollectionParentID() != $parent->getCollectionID()) {
                    $p->move($parent);
                }
            }else{
                $p = $parent->add($ct, $data);
            }

            Event::saveData($p);

            $event_item = New EventItemDates($p,true);

            print Loader::helper('json')->encode(array('success'));
        }else{
            $errors = $error->getList();
            print Loader::helper('json')->encode($errors);
        }
    }

    protected function validate($error)
    {

        $vt = Loader::helper('validation/strings');
        $vn = Loader::Helper('validation/numbers');
        $dt = Loader::helper("form/date_time");
        $dth = Loader::helper('form/datetimetime');
        $request = \Request::getInstance();
        $recur = null;

        if (!$vn->integer($request->get('cParentID'))) {
            $error->add(t('You must choose a parent page for this event entry.'));
        }

        if (!$vt->notempty($request->get('eventTitle'))) {
            $error->add(t('Title is required'));
        }


        //akID['.$ctKey.'][atSelectOptionID][]
        Loader::model("attribute/categories/collection");
        $akdte = CollectionAttributeKey::getByHandle('event_thru');
        $akdteKey = $akdte->getAttributeKeyID();
        $akrc = CollectionAttributeKey::getByHandle('event_recur');
        $akrcKey = $akrc->getAttributeKeyID();
        $akct = CollectionAttributeKey::getByHandle('event_category');
        $ctKey = $akct->getAttributeKeyID();
        foreach ($request->get('akID') as $key => $value) {
            if ($key == $ctKey) {
                foreach ($value as $type => $values) {
                    if ($type == 'atSelectNewOption') {
                        foreach ($values as $cat => $valued) {
                            if ($valued == '') {
                                $error->add(t('Categories must have a value'));
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

        $dates = $request->get('akID');
        $dates = $dates[$dates_akID];

        $date_count = count($dates);
        if ($date_count == 1) {
            $error->add(t('You must have at least one date.'));
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
                $error->add(
                    t(
                        'Your Recurring End Date value may not be earlier than or equal to the last date in your date set when the "Recuring" option is set'
                    )
                );
            }
        }

        if ($recur == 'daily' && $date_count > 1 && $diff > 0) {
            $error->add(t('Date Set\'s of more than one day may only recur by week or month.'));
        }

        if ($recur == 'weekly' && $diff >= 7) {
            $error->add(t('You may not have a Date Set span more than 7 days when recurring weekly.'));
        }

        if ($recur == 'monthly' && $diff >= 29) {
            $error->add(t('You may not have a Date Set span more than 29 days when recurring Monthly.'));
        }

        return $error;
    }
}