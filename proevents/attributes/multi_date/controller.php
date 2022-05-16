<?php  
namespace Concrete\Package\Proevents\Attribute\MultiDate;

use \Concrete\Package\Proevents\Src\ProEvents\EventItemDates;
use \Concrete\Core\Attribute\Controller as AttributeTypeController;
use \Concrete\Core\Page\Page;
use Loader;
use Request;
use Log;

class Controller extends AttributeTypeController
{

    public $helpers = array('form');

    protected $searchIndexFieldDefinition = array(
        'type' => 'string',
        'options' => array('default' => null, 'notnull' => false)
    );

    public function saveKey($data)
    {
        $akDateDisplayMode = $data['akDateDisplayMode'];
        if (!$akDateDisplayMode) {
            $akDateDisplayMode = 'date_time';
        }
        $this->setDisplayMode($akDateDisplayMode);
    }

    public function setDisplayMode($akDateDisplayMode)
    {
        $dbs = Loader::db();
        $ak = $this->getAttributeKey();
        $dbs->Replace(
            'atDateTimeSettings',
            array(
                'akID' => $ak->getAttributeKeyID(),
                'akDateDisplayMode' => $akDateDisplayMode
            ),
            array('akID'),
            true
        );
    }

    public function type_form()
    {
        $this->load();
    }

    public function getDisplayValue()
    {
        $v = $this->getValue();
        if ($v == '' || $v == false) {
            return '';
        }

        $ele = Loader::helper('form/date_time');
        $tm = new TimeHelper();
        $dates = explode(':^:', $v);
        $r = '';
        foreach ($dates as $date) {
            $vars = explode(':-:', $date);
            //if(date('Y-m-d H:i',strtotime($vars[0])) >= date('Y-m-d H:i')){
            $i++;
            //echo $vars[0]. ' - ' .$vars[1];
            //echo '<br/>';

            if (date(DATE_APP_DATE_PICKER, strtotime($vars[0])) >= date(DATE_APP_DATE_PICKER)) {
                if ($print !== false) {
                    $v2 = date('H:i:s', strtotime($vars[0]));
                    if ($v2 != '00:00:00') {
                        $r .= date(DATE_APP_DATE_PICKER . ' H:i a', strtotime($vars[0]));
                    } else {
                        $r .= date(DATE_APP_GENERIC_MDY, strtotime($vars[0]));
                    }
                    if ($vars[1]) {
                        $r .= '&nbsp; to &nbsp;';
                        $r .= date('H:i a', strtotime($vars[1]));
                    }
                    $r .= '<br/>';
                }
            }
        }

        return $r;
    }

    public function searchForm($list)
    {
        $request = \Request::getInstance();
        $dateFrom = $request->get('date_from'. $this->attributeKey->akID);
        if ($dateFrom) {
            $dateFrom = date('Y-m-d', strtotime($dateFrom));
            //use this function to filter out NOT LIKE
            //the NOT LIKE version also includes null values in the result.
            if ($this->request('not_like') == 1) {
                $list->filterByAttribute(
                    $this->attributeKey->getAttributeKeyHandle(),
                    '%' . $dateFrom . '%',
                    'IS NULL OR ak_' . $this->attributeKey->getAttributeKeyHandle() . ' NOT LIKE'
                );
            } else {
                $list->filterByAttribute($this->attributeKey->getAttributeKeyHandle(), '%' . $dateFrom . '%', 'LIKE');
            }
        }
        return $list;
    }

    public function form()
    {
        $this->load();
        $dt = Loader::helper('form/date_time');
        $dtt = Loader::helper('form/datetimetime');
        $tm = Loader::helper('form/time');
        $caValue = $this->getValue();

        $this->set('dtt', $dtt);

        switch ($this->akDateDisplayMode) {
            case 'date':
                $this->set('type', 'date');
                $this->set('akval', $this->attributeKey->akID);
                $this->set('values', $caValue);
                break;
            case 'date_time_time':
                //print $dt->datetime($this->field('value'), $caValue);
                //print ' to ';
                //print $dtt->timex($this->field('value'), $caValue);
                $this->set('tm', $tm);
                $this->set('type', 'date_time_time');
                $this->set('akval', $this->attributeKey->akID);
                $this->set('values', $caValue);
                break;
            default:
                $this->set('type', 'date_exclude');
                $this->set('akval', $this->attributeKey->akID);
                $this->set('values', $caValue);
                break;
        }
    }

    public function validateForm($data)
    {
        return $data['value'] != '';
    }

    public function getValue()
    {
        $dbs = Loader::db();
        $value = $dbs->GetOne("select value from atDateTimeTime where avID = ?", array($this->getAttributeValueID()));
        return $value;
    }

    public function search()
    {
        $dt = Loader::helper('form/date_time');
        $this->load();
        switch ($this->akDateDisplayMode) {
            case 'date_exclude':
                $html = '<input type="text" name="date_from' . $this->attributeKey->akID . '" id="date_from' . $this->attributeKey->akID . '" class="ccm-input-text input-small" value=""/>';
                $html .= '<input type="hidden" value="1" name="akID[' . $this->attributeKey->akID . '][not_like]"/>';
                break;
            default:
                $html = '<input type="text" name="date_from' . $this->attributeKey->akID . '" id="date_from' . $this->attributeKey->akID . '" class="ccm-input-text input-small" value=""/>';
                $html .= '<input type="checkbox" value="1" name="akID[' . $this->attributeKey->akID . '][not_like]"/> Exclude Date.';
                break;
        }
        print $html;
    }

    public function saveValue($value)
    {
        $dbs = Loader::db();
        $dbs->Replace('atDateTimeTime', array('avID' => $this->getAttributeValueID(), 'value' => $value), 'avID', true);
    }

    public function duplicateKey($newAK)
    {
        $this->load();
        $dbs = Loader::db();
        $dbs->Execute(
            'insert into atDateTimeSettings (akID, akDateDisplayMode) values (?, ?)',
            array($newAK->getAttributeKeyID(), $this->akDateDisplayMode)
        );
    }

    public function saveForm($data)
    {
        $this->load();
        $db = Loader::db();
        $dt = Loader::helper('form/date_time');
        $dtt = Loader::helper('form/datetimetime');
        $tm = Loader::helper('form/time');


        switch ($this->akDateDisplayMode) {
            case 'text':
                $this->saveValue($data['value']);
                break;
            case 'date':
                if (is_array($data)) {
                    sort($data);
                    foreach ($data as $date) {
                        if ($i) {
                            $value .= ':^:';
                        }
                        $value .= $dtt->getReformattedDate($date['value']);
                        $i++;
                    }
                } else {
                    $value = '';
                }
                $this->saveValue($value);
                break;
            case 'date_time':
                $value = $dt->translate('value', $data);
                $this->saveValue($value);
                break;
            case 'date_time_time':
                if (is_array($data)) {
                    $value = '';
                    foreach ($data as $date) {
                        if ($date['date_dt']) {
                            if ($i) {
                                $value .= ':^:';
                            }
                            $value .= $dtt->translate('date', null, $date);
                            $i++;
                        }
                    }
                    $this->saveValue($value);
                }
                break;
            case 'date_exclude':
                if (is_array($data)) {
                    sort($data);
                    foreach ($data as $date) {
                        if ($i) {
                            $value .= ':^:';
                        }
                        $value .= $dtt->getReformattedDate($date['1_dt']);
                        $i++;
                    }
                    //var_dump($value);exit;
                    $this->saveValue($value);
                }
                break;
        }

    }

    protected function load()
    {
        $ak = $this->getAttributeKey();
        if (!is_object($ak)) {
            return false;
        }

        $dbs = Loader::db();
        $row = $dbs->GetRow(
            'select akDateDisplayMode from atDateTimeSettings where akID = ?',
            $ak->getAttributeKeyID()
        );
        $this->akDateDisplayMode = $row['akDateDisplayMode'];

        $this->set('akDateDisplayMode', $this->akDateDisplayMode);
    }

    public function deleteKey()
    {
        $dbs = Loader::db();
        $arr = $this->attributeKey->getAttributeValueIDList();
        foreach ($arr as $id) {
            $dbs->Execute('delete from atDateTime where avID = ?', array($id));
        }
    }

    public function deleteValue()
    {
        $dbs = Loader::db();
        $dbs->Execute('delete from atDateTime where avID = ?', array($this->getAttributeValueID()));
    }

}