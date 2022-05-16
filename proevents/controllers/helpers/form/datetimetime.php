<?php  
namespace Concrete\Package\Proevents\Controller\Helpers\Form;

use \Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;
use Loader;
use Log;
use Core;
use Package;

class Datetimetime
{


    public function translate($field, $akID = null, $arr = null)
    {
        if ($arr == null) {
            $arr = $_POST;
        }

        $pkg = Package::getByHandle('proevents');
        $tformat = $pkg->getConfig()->get('formatting.time', false);

        if (isset($arr[$field . '_dt'])) {

            if ($tformat == '24') {

                if ($arr[$field . '_st_h'] == 12) {
                    $arr[$field . '_st_a'] = 'PM';
                } elseif ($arr[$field . '_st_h'] < 12) {
                    if ($arr[$field . '_st_h'] == 0) {
                        $arr[$field . '_st_h'] = 12;
                    }
                    $arr[$field . '_st_a'] = 'AM';
                } else {
                    $arr[$field . '_st_h'] = $arr[$field . '_st_h'] - 12;
                    $arr[$field . '_st_a'] = 'PM';
                }

                if ($arr[$field . '_end_h'] == 12) {
                    $arr[$field . '_end_a'] = 'PM';
                } elseif ($arr[$field . '_end_h'] < 12) {
                    if ($arr[$field . '_end_h'] == 0) {
                        $arr[$field . '_end_h'] = 12;
                    }
                    $arr[$field . '_end_a'] = 'AM';
                } else {
                    $arr[$field . '_end_h'] = $arr[$field . '_end_h'] - 12;
                    $arr[$field . '_end_a'] = 'PM';
                }

            } else {

                if ($arr[$field . '_st_h'] > 12) {
                    $arr[$field . '_st_h'] = $arr[$field . '_st_h'] - 12;
                    $arr[$field . '_st_a'] = 'PM';
                }

                if ($arr[$field . '_end_h'] > 12) {
                    $arr[$field . '_end_h'] = $arr[$field . '_end_h'] - 12;
                    $arr[$field . '_end_a'] = 'PM';
                }

                if ($arr[$field . '_st_a'] == '') {
                    $arr[$field . '_st_a'] = 'AM';
                }

                if ($arr[$field . '_end_a'] == '') {
                    $arr[$field . '_end_a'] = 'AM';
                }

            }

            $dt = $arr[$field . '_dt'];

            $str = $dt . ' ' . $arr[$field . '_st_h'] . ':' . $arr[$field . '_st_m'] . ' ' . $arr[$field . '_st_a'] . ' ' . $arr[$field . '_end_h'] . ':' . $arr[$field . '_end_m'] . ' ' . $arr[$field . '_end_a'];


            return $str;
        }

        if ($akID) {

            if ($tformat == '24') {

                if ($arr[$field . '_st_h'] == 12) {
                    $arr[$field . '_st_a'] = 'PM';
                } elseif ($arr[$field . '_st_h'] < 12) {
                    if ($arr[$field . '_st_h'] == 0) {
                        $arr[$field . '_st_h'] = 12;
                    }
                    $arr[$field . '_st_a'] = 'AM';
                } else {
                    $arr[$field . '_st_h'] = $arr[$field . '_st_h'] - 12;
                    $arr[$field . '_st_a'] = 'PM';
                }

                if ($arr[$field . '_end_h'] == 12) {
                    $arr[$field . '_end_a'] = 'PM';
                } elseif ($arr[$field . '_end_h'] < 12) {
                    if ($arr[$field . '_end_h'] == 0) {
                        $arr[$field . '_end_h'] = 12;
                    }
                    $arr[$field . '_end_a'] = 'AM';
                } else {
                    $arr[$field . '_end_h'] = $arr[$field . '_end_h'] - 12;
                    $arr[$field . '_end_a'] = 'PM';
                }

            } else {

                if ($arr[$field . '_st_h'] > 12) {
                    $arr[$field . '_st_h'] = $arr[$field . '_st_h'] - 12;
                    $arr[$field . '_st_a'] = 'PM';
                }

                if ($arr[$field . '_end_h'] > 12) {
                    $arr[$field . '_end_h'] = $arr[$field . '_end_h'] - 12;
                    $arr[$field . '_end_a'] = 'PM';
                }

                if ($arr[$field . '_st_a'] == '') {
                    $arr[$field . '_st_a'] = 'AM';
                }

                if ($arr[$field . '_end_a'] == '') {
                    $arr[$field . '_end_a'] = 'AM';
                }

            }

            $arr = $arr['akID'][$akID];
            $dt = $this->translateDate($arr[$field . '_st_dt']);
            $str = $dt . ' ' . $arr[$field . '_st_h'] . ':' . $arr[$field . '_st_m'] . ' ' . $arr[$field . '_st_a'] . ' ' . $arr[$field . '_end_h'] . ':' . $arr[$field . '_end_m'] . ' ' . $arr[$field . '_end_a'];


            return $str;
        }

    }

    /**
     * translates a given page objects event_multidate attribute
     * into a nice array of dates and times
     **/
    public function translate_from($c)
    {
        $i = 0;
        Loader::model('attribute/categories/collection');
        $emdd = CollectionAttributeKey::getByHandle('event_multidate');
        $date_multi = $c->getCollectionAttributeValue($emdd);

        $date_multi_array = explode(':^:', $date_multi);
        foreach ($date_multi_array as $dated) {
            $i++;
            $date_sub = explode(' ', $dated);
            $dates_array[$i]['dsID'] = $i;
            $dates_array[$i]['date'] = $date_sub[0];
            $stime = $date_sub[1] . ' ' . $date_sub[2];
            $etime = $date_sub[3] . ' ' . $date_sub[4];
            $dates_array[$i]['start'] = $stime;
            $dates_array[$i]['end'] = $etime;

        }

        return $dates_array;
    }


    /**
     * translates a given string to a nice array of dates and times
     * input: cID date starttime:-:endtime
     * output: array(date=>value,start=>value,end=>value)
     **/
    public function translate_from_string($date_info)
    {
        $i = 0;
        $date_multi_array = explode(':^:', $date_info);
        foreach ($date_multi_array as $dated) {
            $i++;
            $date_sub = explode(' ', $dated);

            $dates_array['date'] = $date_sub[0];
            $stime = $date_sub[1] . ' ' . $date_sub[2];
            $etime = $date_sub[3] . ' ' . $date_sub[4];

            $dates_array['start'] = $this->convert_to_time($stime);
            $dates_array['end'] = $this->convert_to_time($etime);
        }
        return $dates_array;
    }

    /**
     * Takes a "string", converts to output string based on time config
     * @param string $string
     * @return string $Time
     */
    public function convert_to_time($time)
    {
        return Loader::helper('date')->formatTime($time);
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
    public function datetimetime($prefix, $value = null, $includeActivation = false, $calendarAutoStart = true, $instance = null)
    {
        if (substr($prefix, -1) == ']') {
            $prefix = substr($prefix, 0, strlen($prefix) - 1);
            $_activate = $prefix . '_activate]';
            $_dt = $prefix . '_dt]';
            $_h = $prefix . '_st_h]';
            $_m = $prefix . '_st_m]';
            $_a = $prefix . '_st_a]';
            $_end_h = $prefix . '_end_h]';
            $_end_m = $prefix . '_end_m]';
            $_end_a = $prefix . '_end_a]';
        } else {
            $_activate = $prefix . '_activate';
            $_dt = $prefix . '_dt';
            $_h = $prefix . '_st_h';
            $_m = $prefix . '_st_m';
            $_a = $prefix . '_st_a';
            $_end_h = $prefix . '_end_h';
            $_end_m = $prefix . '_end_m';
            $_end_a = $prefix . '_end_a';
        }

        $dh = Core::make('helper/date');
        /* @var $dh \Concrete\Core\Localization\Service\Date */
        //$timeFormat = $dh->getTimeFormat();

        $pkg = Package::getByHandle('proevents');
        $timeFormat = $pkg->getConfig()->get('formatting.time', false);
        $datePickerFormat = $pkg->getConfig()->get('formatting.datepicker', false);

        $value = $value?$value:date('Y-m-d').' 1:00 PM 3:00 PM';
        /* we have a blank date! */
        if($value == ' 00:00 AM 00:00 AM'){
            $value = date('Y-m-d').' 1:00 PM 3:00 PM';
        }
        /* explode and create end time */
        $value_pairs = explode(' ', $value);

        $sdate = $value_pairs[0] . ' ' . $value_pairs[1] . ' ' . $value_pairs[2];
        $edate = $value_pairs[0] . ' ' . $value_pairs[3] . ' ' . $value_pairs[4];


        list($dateYear, $dateMonth, $dateDay, $timeHour, $timeMinute) = explode(
            ',',
            $dh->formatCustom('Y,n,j,G,i', strtotime($sdate))
        );
        list($edateYear, $edateMonth, $edateDay, $etimeHour, $etimeMinute) = explode(
            ',',
            $dh->formatCustom('Y,n,j,G,i', strtotime($edate))
        );

        $timeMinute = intval($timeMinute);
        if ($timeFormat == 12) {
            $timeAMPM = ($timeHour < 12) ? 'AM' : 'PM';
            $timeHour = ($timeHour % 12);
            if ($timeHour == 0) {
                $timeHour = 12;
            }
        }
        if ($value === '') {
            $defaultDateJs = '""';
        } else {
            $defaultDateJs = "new Date($dateYear, $dateMonth - 1, $dateDay)";
        }
        $id = preg_replace("/[^0-9A-Za-z-]/", "_", $prefix);
        $html = '';
        $disabled = false;
        $html .= '<div class="form-inline">';
        if ($includeActivation) {
            if ($value) {
                $activated = 'checked';
            } else {
                $disabled = 'disabled';
            }
            $html .= '<input type="checkbox" id="' . $id . '_activate" class="ccm-activate-date-time" ccm-date-time-id="' . $id . '" name="' . $_activate . '" ' . $activated . ' />';
        }

        $html .= '<div class="form-group"><span class="ccm-input-date-wrapper" id="' . $id . '_dw"><input id="' . $id . '_dt_pub" class="form-control ccm-input-date"  ' . $disabled . ' /><input id="' . $id . '_dt" name="' . $_dt . '" type="hidden" ' . $disabled . ' /></span>';


        $html .= '<span class="ccm-input-time-wrapper form-inline" id="' . $id . '_st_tw">';
        $html .= '<select class="form-control" id="' . $id . '_st_h" name="' . $_h . '" ' . $disabled . '>';

        $hourStart = ($timeFormat == 12) ? 1 : 0;
        $hourEnd = ($timeFormat == 12) ? 12 : 23;
        for ($i = $hourStart; $i <= $hourEnd; $i++) {
            if ($i == $timeHour) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $html .= '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
        }
        $html .= '</select>:';
        $html .= '<select class="form-control"  id="' . $id . '_st_m" name="' . $_m . '" ' . $disabled . '>';
        for ($i = 0; $i <= 59; $i++) {
            if ($i == $timeMinute) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $html .= '<option value="' . sprintf('%02d', $i) . '" ' . $selected . '>' . sprintf(
                    '%02d',
                    $i
                ) . '</option>';
        }
        $html .= '</select>';
        if ($timeFormat == 12) {
            $html .= '<select class="form-control" id="' . $id . '_st_a" name="' . $_a . '" ' . $disabled . '>';
            $html .= '<option value="AM" ';
            if ($timeAMPM == 'AM') {
                $html .= 'selected';
            }
            $html .= '>';
            // This prints out the translation of "AM" in the current language
            $html .= $dh->date('A', mktime(1));
            $html .= '</option>';
            $html .= '<option value="PM" ';
            if ($timeAMPM == 'PM') {
                $html .= 'selected';
            }
            $html .= '>';
            // This prints out the translation of "PM" in the current language
            $html .= $dh->date('A', mktime(13));
            $html .= '</option>';
            $html .= '</select>';
        }
        $html .= '</span>';


        $etimeMinute = intval($etimeMinute);
        if ($timeFormat == 12) {
            $timeAMPM = ($etimeHour < 12) ? 'AM' : 'PM';
            $etimeHour = ($etimeHour % 12);
            if ($etimeHour == 0) {
                $etimeHour = 12;
            }
        }
        $html .= t(' to  ');
        $html .= '<span class="ccm-input-time-wrapper form-inline" id="' . $id . '_st_tw">';
        $html .= '<select class="form-control" id="' . $id . '_end_h" name="' . $_end_h . '" ' . $disabled . '>';

        $hourStart = ($timeFormat == 12) ? 1 : 0;
        $hourEnd = ($timeFormat == 12) ? 12 : 23;
        for ($i = $hourStart; $i <= $hourEnd; $i++) {
            if ($i == $etimeHour) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $html .= '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
        }
        $html .= '</select>:';
        $html .= '<select class="form-control"  id="' . $id . '_end_m" name="' . $_end_m . '" ' . $disabled . '>';
        for ($i = 0; $i <= 59; $i++) {
            if ($i == $etimeMinute) {
                $selected = 'selected';
            } else {
                $selected = '';
            }
            $html .= '<option value="' . sprintf('%02d', $i) . '" ' . $selected . '>' . sprintf(
                    '%02d',
                    $i
                ) . '</option>';
        }
        $html .= '</select>';
        if ($timeFormat == 12) {
            $html .= '<select class="form-control" id="' . $id . '_end_a" name="' . $_end_a . '" ' . $disabled . '>';
            $html .= '<option value="AM" ';
            if ($timeAMPM == 'AM') {
                $html .= 'selected';
            }
            $html .= '>';
            // This prints out the translation of "AM" in the current language
            $html .= $dh->date('A', mktime(1));
            $html .= '</option>';
            $html .= '<option value="PM" ';
            if ($timeAMPM == 'PM') {
                $html .= 'selected';
            }
            $html .= '>';
            // This prints out the translation of "PM" in the current language
            $html .= $dh->date('A', mktime(13));
            $html .= '</option>';
            $html .= '</select>';
        }
        $html .= '</span>';

        $html .= '<a href="javascript:;" onClick="$(this).parent().parent().remove(); checkDateMessage_'.$instance.'();"> <i class="fa fa-trash"></i></a>';

        $html .= '</div></div>';
        $jh = Core::make('helper/json');
        /* @var $jh \Concrete\Core\Http\Service\Json */
        if ($calendarAutoStart) {
            $html .= '<script type="text/javascript">$(function () {
                $("#' . $id . '_dt_pub").datepicker({
                    dateFormat: "' . $datePickerFormat . '",
                    altFormat: "yy-mm-dd",
                    altField: "#' . $id . '_dt",
                    changeYear: true,
                    showAnim: \'fadeIn\'
                }).datepicker("setDate" , ' . $defaultDateJs . '); })</script>';
        }
        // first we add a calendar input

        if ($includeActivation) {
            $html .= <<<EOS
			<script type="text/javascript">$("#{$id}_activate").click(function () {
				if ($(this).get(0).checked) {
					$("#{$id}_dw input").each(function () {
						$(this).get(0).disabled = false;
					});
					$("#{$id}_tw select").each(function () {
						$(this).get(0).disabled = false;
					});
				} else {
					$("#{$id}_dw input").each(function () {
						$(this).get(0).disabled = true;
					});
					$("#{$id}_tw select").each(function () {
						$(this).get(0).disabled = true;
					});
				}
			});
			</script>
EOS;

        }

        return $html;

    }

    /**
     * Creates form fields and JavaScript calendar includes for a particular item but includes only calendar controls (no time.)
     * <code>
     *     $dh->date('yourStartDate', '2008-07-12 3:00:00');
     * </code>
     * @param string $prefix
     * @param string $value
     * @param bool $includeActivation
     * @param bool $calendarAutoStart
     */
    public function date($field, $value = null, $calendarAutoStart = true)
    {
        $dh = Core::make('helper/date'); /* @var $dh \Concrete\Core\Localization\Service\Date */
        $request = \Request::getInstance();
        $pkg = Package::getByHandle('proevents');
        $datePickerFormat = $pkg->getConfig()->get('formatting.datepicker', false);

        $id = preg_replace("/[^0-9A-Za-z-]/", "_", $field);
        if ($request->get($field)) {
            $timestamp = ($request->get($field)) ?  strtotime($request->get($field)) : false;
        } elseif ($value) {
            $timestamp = @strtotime($value);
        } elseif ($value === '') {
            $timestamp = false;
        } else {
            // Today (in the user's timezone)
            $timestamp = strtotime($dh->formatCustom('Y-m-d'));
        }
        if ($timestamp) {
            $defaultDateJs = 'new Date(' . implode(', ', array(date('Y', $timestamp), date('n', $timestamp) - 1, date('j', $timestamp))) . ')';
        } else {
            $defaultDateJs = '""';
        }
        $html = '';
        $html .= '<span class="ccm-input-date-wrapper" id="' . $id . '_dw"><input id="' . $id . '_pub" class="form-control ccm-input-date"  /><input id="' . $id . '" name="' . $field . '" type="hidden"  /></span>';
        $jh = Core::make('helper/json'); /* @var $jh \Concrete\Core\Http\Service\Json */
        if ($calendarAutoStart) {
            $html .= '<script type="text/javascript">$(function () {
                $("#' . $id . '_pub").datepicker({
                    dateFormat: "' . $datePickerFormat . '",
                    altFormat: "yy-mm-dd",
                    altField: "#' . $id . '",
                    changeYear: true,
                    showAnim: \'fadeIn\'
                }).datepicker( "setDate" , ' . $defaultDateJs . ' ); });</script>';
        }

        return $html;

    }


    public function getReformattedDate($date, $debug = false)
    {

        $dh = Core::make('helper/date');

        $sdate = new \DateTime($date, $dh->getTimezone('user'));

        return $sdate->format('Y-m-d');
    }

}

?>