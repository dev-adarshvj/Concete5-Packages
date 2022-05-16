<?php  
/**
 * @package Helpers
 * @category Concrete
 * @subpackage Forms
 * @author Andrew Embler <andrew@concrete5.org>
 * @copyright  Copyright (c) 2003-2008 Concrete5. (http://www.concrete5.org)
 * @license    http://www.concrete5.org/license/     MIT License
 */


namespace Concrete\Package\Proevents\Controller\Helpers\Form;

use Loader;
use Package;

class Time
{

    /**
     * Takes a "field" and grabs all the corresponding disparate fields from $_POST and translates into a timestamp
     * @param string $field
     * @param array $arr
     * @return string $dateTime
     */
    public function translate($field, $arr = null)
    {
        if ($arr == null) {
            $arr = $_POST;
        }

        if (DATE_FORM_HELPER_FORMAT_HOUR == '12') {
            $str = $dt . ' ' . $arr[$field . '_h'] . ':' . $arr[$field . '_m'] . ' ' . $arr[$field . '_a'];
        } else {
            $str = $dt . ' ' . $arr[$field . '_h'] . ':' . $arr[$field . '_m'];
        }
        return date('H:i:s', strtotime($str));

    }

    /**
     * Creates form fields and JavaScript calendar includes for a particular item
     * <code>
     *     $dh->datetime('yourStartDate', '2008-07-12 3:00:00');
     * </code>
     * @param string $prefix
     * @param string $value
     * @param bool $includeActivation
     * @param bool $calendarAutoStart
     */
    public function timex($prefix, $value = null)
    {
        if (substr($prefix, -1) == ']') {
            $prefix = substr($prefix, 0, strlen($prefix) - 1);
            $_activate = $prefix . '_activate]';
            $_h = $prefix . '_h]';
            $_m = $prefix . '_m]';
            $_a = $prefix . '_a]';
        } else {
            $_activate = $prefix . '_activate';
            $_h = $prefix . '_h';
            $_m = $prefix . '_m';
            $_a = $prefix . '_a';
        }

        $dfh = (DATE_FORM_HELPER_FORMAT_HOUR == '12') ? 'h' : 'H';
        $dfhe = (DATE_FORM_HELPER_FORMAT_HOUR == '12') ? '12' : '23';
        $dfhs = (DATE_FORM_HELPER_FORMAT_HOUR == '12') ? '1' : '0';
        if ($value != null) {
            $dt = date(DATE_APP_GENERIC_MDY, strtotime($value));
            $h = date($dfh, strtotime($value));
            $m = date('i', strtotime($value));
            $a = date('A', strtotime($value));
        } else {
            $dt = date(DATE_APP_GENERIC_MDY);
            $h = date($dfh);
            $m = date('i');
            $a = date('A');
        }
        $id = preg_replace("/[^0-9A-Za-z-]/", "_", $prefix);
        $html = '';
        $disabled = false;

        $html .= '<span class="ccm-input-time-wrapper form-inline" id="' . $id . '_tw">';
        $html .= '<select id="' . $id . '_h" name="' . $_h . '" ' . $disabled . ' class="form-control">';
        for ($i = $dfhs; $i <= $dfhe; $i++) {
            if ($h == $i) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $html .= '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
        }
        $html .= '</select>:';
        $html .= '<select id="' . $id . '_m" name="' . $_m . '" ' . $disabled . ' class="form-control">';
        for ($i = 0; $i <= 59; $i++) {
            if ($m == sprintf('%02d', $i)) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $html .= '<option value="' . sprintf('%02d', $i) . '"' . $selected . '>' . sprintf(
                    '%02d',
                    $i
                ) . '</option>';
        }
        $html .= '</select>';
        if (DATE_FORM_HELPER_FORMAT_HOUR == '12') {
            $html .= '<select id="' . $id . '_a" name="' . $_a . '" ' . $disabled . ' class="form-control">';
            $html .= '<option value="AM" ';
            if ($a == 'AM') {
                $html .= 'selected';
            }
            $html .= '>AM</option>';
            $html .= '<option value="PM" ';
            if ($a == 'PM') {
                $html .= 'selected';
            }
            $html .= '>PM</option>';
            $html .= '</select>';
        }
        $html .= '</span>';


        return $html;

    }

    public function formatTime($time){
        $pkg = Package::getByHandle('proevents');
        $tformat = $pkg->getConfig()->get('formatting.time', false);
        if($tformat==12 || !$tformat){
            $tf = t('g:i a');
        }else{
            $tf = t('H:i');
        }
        return date($tf, strtotime($time));
    }
}
