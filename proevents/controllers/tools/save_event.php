<?php  
namespace Concrete\Package\Proevents\Controller\Tools;

use Loader;
use Page;
use Block;
use Concrete\Core\Controller\Controller as RouteController;
use \Concrete\Package\Proevents\Src\ProEvents\EventItem;

class SaveEvent extends RouteController
{

    public function save()
    {
        $request = \Request::getInstance();
        $db = loader::db();
        $ueID = $db->getOne(
            "SELECT ueID FROM btProEventUserSaved WHERE eventID = ? AND uID = ?",
            array($request->get('event'), $request->get('user'))
        );

        if ($ueID) {
            $db->execute("DELETE FROM btProEventUserSaved WHERE ueID = ?", array($ueID));
        } else {
            $db->execute(
                "INSERT INTO btProEventUserSaved (eventID,uID) VALUES (?,?)",
                array($request->get('event'), $request->get('user'))
            );
        }

        exit;
    }

}