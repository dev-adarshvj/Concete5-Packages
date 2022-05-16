<?php

namespace Concrete\Package\ServiceLocations\Src\Entity;
use Concrete\Package\ServiceLocations\Src\Entity\SaveContact;
use \Concrete\Core\Legacy\DatabaseItemList;


class SaveContactListing extends DatabaseItemList
{

    function __construct()
    {
        
        $this->setQuery('select id from savecontact');
		

    }
	public function get($itemsToGet = 0, $offset = 0)
    {
        $records = array();
        $r = parent::get($itemsToGet, $offset);

        foreach ($r as $row) {
            $item = SaveContact::getTypeByID($row['id']);
            $records[] = $item;
        }

        return $records;
    }
  
}
