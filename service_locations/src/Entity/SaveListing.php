<?php

namespace Concrete\Package\ServiceLocations\Src\Entity;
use Concrete\Package\ServiceLocations\Src\Entity\SaveForm;
use \Concrete\Core\Legacy\DatabaseItemList;


class SaveListing extends DatabaseItemList
{

    function __construct()
    {
        
        $this->setQuery('select id from saveform');
		

    }
	public function get($itemsToGet = 0, $offset = 0)
    {
        $records = array();
        $r = parent::get($itemsToGet, $offset);

        foreach ($r as $row) {
            $item = SaveForm::getTypeByID($row['id']);
            $records[] = $item;
        }

        return $records;
    }
    public function filterByKeywords($keyword)
    { 
      if ($keyword != '') {
          $db = \Database::connection();
		  if(is_numeric($keyword)){
				$this->filter(false, '( id = ' . $keyword . ')');  
		  }else{
          $qkeywords = $db->quote('%' . $keyword . '%');
          $this->filter(false, '( name like ' . $qkeywords . '
  			OR age like ' . $qkeywords . ' OR email like ' . $qkeywords . ')');
		  }
      }
    }
}
