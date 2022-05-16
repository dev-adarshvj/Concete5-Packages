<?php  
namespace Concrete\Package\Proevents\Attribute\Price;

use \Concrete\Attribute\Text\Controller as TextAttributeTypeController;
use Loader;

class Controller extends TextAttributeTypeController
{

    protected $searchIndexFieldDefinition = false;

    public function form()
    {
        if (is_object($this->attributeValue)) {
            $value = Loader::helper('text')->entities($this->getAttributeValue()->getValue());
        }
        if ($value) {
            $value = number_format($value, 2, '.', ',');
        }
        $this->set('value', $value);
        print '<span style="float: left; padding-right: 7px;">$</span>' . Loader::helper('form')->text(
                $this->field('value'),
                $value,
                array('size' => '10', 'class' => 'price small')
            );
    }

    public function saveValue($value)
    {
        $db = Loader::db();
        $value = str_replace(',', '', $value);
        $value = str_replace('$', '', $value);
        if ($this->getAttributeValueID()) {
            $db->Replace('atDefault', array('avID' => $this->getAttributeValueID(), 'value' => $value), 'avID', true);
        }
    }

    public function saveForm($data)
    {
        $this->saveValue($data['value']);
    }

}