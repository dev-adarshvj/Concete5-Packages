<?php  
namespace Concrete\Package\Proevents\Src\ProEvents;

use \Concrete\Core\Legacy\Model;
use \Concrete\Core\Page\PageList;
use \Concrete\Core\Page\Page;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;
use Loader;
use Concrete\Package\Proevents\Src\ProEvents\EventItemDate;

/**
 *
 * An object that allows a filtered list of events to be returned.
 * @package ProEvents
 *
 **/
class EventItem extends Model
{

    var $event;
    var $eID;
    var $num;
    var $dates_array;
    var $date_item;
    var $times_array;
    var $next_dates_array;


    function __construct($event, $eID = null)
    {

        $this->event = $event;

        $this->eID = $event->eID;

        if ($eID) {
            $this->eID = $eID;
        }

        $dth = Loader::helper('form/datetimetime');
        $this->dates_array = $dth->translate_from($event);
        $this->num = count($this->dates_array);
        $this->date_item = $this->date_array['date'];

        //if($this->date_item){
        $this->setEventItemInto();

        $this->setNextNumDates();
        //}
    }


    public function setEventItemInto()
    {
        $db = Loader::db();
        $row = $db->getRow("SELECT * FROM btProEventDates WHERE eID = ?", array($this->eID));
        $date = new EventItemDate($row);
        $this->description = $date->description;
        $this->title = $date->title;

        $dth = Loader::helper('form/datetimetime');

        $times[0]['start'] = $this->dates_array['start'];
        $times[0]['end'] = $this->dates_array['end'];
        $this->times_array = $times;

        if ($this->eID) {
            $this->date_item = $db->getOne("SELECT date FROM btProEventDates WHERE eID = ?", array($this->eID));
        }else{
	        $data = array(
	        	$this->dates_array[1]['date'],
	        	date('H:i:s',strtotime($this->dates_array[1]['start'])),
	        	date('H:i:s',strtotime($this->dates_array[1]['end'])),
	        	$this->event->getCollectionID()
	        );
	        $date_info = $db->getRow("SELECT * FROM btProEventDates WHERE date = ? AND sttime = ? AND entime = ? AND eventID = ?",$data);
	        $this->eID = $date_info['eID'];
	        $this->date_item = $this->dates_array[1]['date'];
        }
    }


    public function getEventItemTimes()
    {
        return $this->next_dates_array;
    }

    public function getEventDescription()
    {
        return $this->description;
    }

    private function setClickedDate()
    {
        $db = Loader::db();
        $this->date_item = $db->getOne("SELECT date FROM btProEventDates WHERE eID = ?", array($this->eID));
        $this->setNextNumDates();
    }


    private function setNextNumDates($grouped = false)
    {
        $db = Loader::db();
        $cID = $this->event->getCollectionID();
        $num = $this->num;

        $date = $this->date_item;

        if ($this->event->getAttribute('event_grouped') > 0) {
            $grouped = $db->getOne("SELECT grouped FROM btProEventDates WHERE eID=?", array($this->eID));
            $r = $db->execute(
                "SELECT * FROM btProEventDates WHERE date >= $date AND eventID = $cID AND grouped = $grouped ORDER BY date,sttime ASC LIMIT $num"
            );
        } else {

            if (!$this->date_item) {
                $r = $db->execute(
                    "SELECT * FROM `btProEventDates` WHERE `eventID` = $cID  ORDER BY `date`,`sttime` ASC LIMIT $num"
                );
            } else {
                $r = $db->execute(
                    "SELECT * FROM btProEventDates WHERE date = $date AND eventID = $cID  ORDER BY date,sttime ASC LIMIT $num"
                );
            }
        }

        while ($row = $r->fetchrow()) {
            $dates[] = new EventItemDate($row);
        }

        if (!$dates) {
            $row = $db->getRow("SELECT * FROM btProEventDates WHERE eID = ? ", array($this->eID));
            $dates[] = new EventItemDate($row);
        }

        $this->next_dates_array = $dates;
    }

    public function getEventItemID()
    {
        return $this->eID;
    }

    public function getEventItemDate()
    {
        return $this->date_item;
    }

    public function getEventItemNextDates()
    {
        return $this->next_dates_array;
    }

    public function getEventItemsNum()
    {
        return $this->num;
    }

    public function getEventItemDatesArray()
    {
        return $this->dates_array;
    }
}