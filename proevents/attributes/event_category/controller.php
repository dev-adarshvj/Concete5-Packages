<?php  
namespace Concrete\Package\Proevents\Attribute\EventCategory;

use \Concrete\Core\Foundation\Object;
use \Concrete\Core\Attribute\DefaultController;
use Loader;

class Controller extends DefaultController
{

    protected $searchIndexFieldDefinition = array(
        'type' => 'string',
        'options' => array('default' => null, 'notnull' => false)
    );


    public function load()
    {
        $ak = $this->getAttributeKey();
        if (!is_object($ak)) {
            return false;
        }

        $db = Loader::db();
        $categories = $db->getAll(
            "SELECT * FROM atEventCategoryOptions WHERE akID = ? ORDER BY ordering ASC",
            array($this->attributeKey->getAttributeKeyID())
        );

        $this->categories = $categories;
        $this->set('categories', $this->categories);

        $this->akID = $this->attributeKey->getAttributeKeyID();
        $this->set('akID', $this->akID);
    }

    public function getValue()
    {
        $db = Loader::db();
        $value = $db->getOne("SELECT value FROM atEventCategory WHERE avID=?", array($this->getAttributeValueID()));
        $this->set('selected_category', $value);
        return $value;
    }

    public function getDisplaySanitizedValue()
    {
        return Loader::helper('text')->entities($this->getValue());
    }

    public function getColorValue($category = null)
    {
        $db = Loader::db();
        $value = $db->getOne("SELECT color FROM atEventCategoryOptions WHERE value=?", array($category));
        return $value;
    }

    public function getSearchIndexValue()
    {
        return $this->getValue();
    }

    public function form()
    {
        $this->load();
        $this->getValue();

        $html = Loader::helper('html');
        $this->addHeaderItem($html->javascript('jquery.js'));
        $this->addFooterItem($html->javascript('colpick.js', 'proevents'));
        $this->addHeaderItem($html->css('colpick.css', 'proevents'));
    }


    public function saveForm($data)
    {
        $db = Loader::db();

        if($data['new_category']['name'] && $data['new_category']['name']!='' && $data['new_category']['color'] != ''){
            $val = array(
                'akID' => $this->attributeKey->getAttributeKeyID(),
                'value' => $data['new_category']['name'],
                'color' => $data['new_category']['color'],
                'ordering' => 0
            );
            $data['value'] = $data['new_category']['name'];

            $db->Replace('atEventCategoryOptions', $val, 'value', true);
        }

        $db->Replace(
            'atEventCategory',
            array('avID' => $this->getAttributeValueID(), 'value' => $data['value']),
            'avID',
            true
        );

    }

    public function saveValue($data)
    {
        $this->saveForm($data);
    }


    public function type_form()
    {

        $this->load();

        $html = Loader::helper('html');
        $this->addFooterItem($html->javascript('colpick.js', 'proevents'));
        $this->addHeaderItem($html->css('colpick.css', 'proevents'));
    }

    public function saveKey($data)
    {
        $db = Loader::db();
        if ($data['category_name']) {
            foreach ($data['category_name'] as $key => $name) {
                $i++;
                $val = array(
                    'akID' => $this->attributeKey->getAttributeKeyID(),
                    'value' => $name,
                    'color' => $data['category_color'][$key],
                    'ordering' => $i
                );
                if ($data['category_id'][$key]) {
                    $db->update("atEventCategoryOptions", $val, array('ecID' => $data['category_id'][$key]));
                } else {
                    $db->insert("atEventCategoryOptions", $val);
                }
            }
        }

        if ($data['category_remove']) {
            foreach ($data['category_remove'] as $ecID) {
                $db->execute("DELETE FROM atEventCategoryOptions WHERE ecID=?", $ecID);
            }
        }
    }

}