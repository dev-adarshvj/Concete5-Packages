<?php  
namespace Concrete\Package\ServiceLocations\Attribute\Fileset;
use Loader;
use FileSet;
class Controller extends \Concrete\Core\Attribute\Controller  {
	
	protected $searchIndexFieldDefinition = array('type' => 'integer', 'options' => array('default' => 0, 'notnull' => false));
	public function form() {

		//Loader::model('file_set');

		$this->set('fileSets', FileSet::getMySets());
		$this->set('name', $this->field('value'));

		if (is_object($this->attributeValue)) {
			$this->set('selected', $this->getAttributeValue()->getValue());
		} else {
			$this->set('selected', 0);
		}
	}
	
	public function saveForm($data) {
		$db = Loader::db();
		$this->saveValue($data['value']);
	}
	public function getValue() {
		$db = Loader::db();
		$value = $db->GetOne("select value from atFileSet where avID = ?", array($this->getAttributeValueID()));
		return $value;	
	}
	public function saveValue($value) {
		$db = Loader::db();
        if(!intval($value)) {
            $value = 0;
        }
		$db->Replace('atFileSet', array('avID' => $this->getAttributeValueID(), 'value' => $value), 'avID', true);
	}
	
	
	public function deleteKey() {
		$db = Loader::db();
		$arr = $this->attributeKey->getAttributeValueIDList();
		foreach($arr as $id) {
			$db->Execute('delete from atFileSet where avID = ?', array($id));
		}
	}
	
	
	public function deleteValue() {
		$db = Loader::db();
		$db->Execute('delete from atFileSet where avID = ?', array($this->getAttributeValueID()));
	}

}