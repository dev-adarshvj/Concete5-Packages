<?php  
namespace Concrete\Package\Proevents\Src\ProEvents;

use \Concrete\Core\Legacy\Model;
use Loader;

/**
 *
 * An object that allows a filtered list of events to be returned.
 * @package ProEvents
 *
 **/
class EventItemDate extends model
{
    var $title;
    var $date;
    var $start;
    var $end;
    var $description;
    var $status;
    var $event_price;
    var $event_qty;

    function __construct($data)
    {

        if (!is_array($data)) {
            $eID = $data;
            $db = Loader::db();
            $data = $db->getRow("SELECT * FROM btProEventDates WHERE eID = ?", array($eID));
        }
        $this->title = $data['title'];
        $this->date = $data['date'];
        $this->start = $data['sttime'];
        $this->end = $data['entime'];
        $this->description = $data['description'];
        $this->status = $data['status'];
        $this->event_price = $data['event_price'];
        $this->event_qty = $data['event_qty'];
    }
}