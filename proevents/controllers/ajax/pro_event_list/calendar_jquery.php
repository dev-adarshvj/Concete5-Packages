<?php  
namespace Concrete\Package\Proevents\Controller\Ajax\ProEventList;

use Loader;
use Page;
use Block;
use Concrete\Core\Controller\Controller;

use \Concrete\Package\Proevents\Src\ProEvents\EventItem;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;

class CalendarJquery extends Controller
{
    protected $viewPath = '/ajax/pro_event_list/calendar_jquery';
    public function view()
    {

        $dth = Loader::helper('form/datetimetime');
        $nh = Loader::helper('navigation');
        $jh = Loader::helper('json');
        $request = \Request::getInstance();
        $bID = $request->get('bID');



        if ($request->get('state')) {
            $c = Page::getByID($request->get('cID'),$request->get('state'));
        } else {
            $c = Page::getByID($request->get('cID'));
        }

        $date = date('Y-m-d', $request->get('start'));
        $date2 = date('Y-m-d', $request->get('end'));

        if (!$request->get('start')) {
            $days_in_month = cal_days_in_month(0, date('m'), date('Y'));
            $date = date('Y-m-1');
            $date2 = date('Y-m-' . $days_in_month);
        }

        $date_origin = $date;

        $b = Block::getByID($bID);

        $controller = $b->getController();

        if ($request->get('ctID') != '') {
            $ctID = $request->get('ctID');
            $controller->ctID = $ctID;
        }

        $settings = $controller->settings;

        $events = $controller->getEvents($date, $date2);
        $el = $controller->el;

        $recured_array = array();
        $events_array = array();

        $i = 0;
        foreach ($events as $date_string => $ep) {
            $i++;

            $date_array = $dth->translate_from_string($ep->multidate);

            $event_item = new EventItem($ep);

            $eID = $event_item->eID;
            $date = $date_array['date'];
            $stime = $date_array['start'];
            $etime = $date_array['end'];

            $id = $ep->getCollectionID();

            $title = $ep->getCollectionName();

            $url = $nh->getLinkToCollection($ep) . '?eID=' . $eID;
            if ($ep->getCollectionAttributeValue('exclude_nav')) {
                $url = '';
            }

            $url = addslashes($url);

            $content = $event_item->getEventDescription();
            if ($controller->truncateSummaries) {
                $content = substr($content, 0, $controller->truncateChars) . '...';
            }

            $exclude = explode(':^:', $ep->getAttribute('event_exclude'));
            sort($exclude);

            $location = $ep->getAttribute('event_local');

            $category = $ep->getAttribute('event_category');

            $akct = CollectionAttributeKey::getByHandle('event_category');
            $tcvalue = $ep->getAttributeValueObject($akct);
            $color = $akct->getAttributeType()->getController()->getColorValue($category);
            if ($color) {
                $color = '#' . $color;
            } else {
                $color = false;
            }

            loader::model("attribute/categories/collection");
            $akrr = CollectionAttributeKey::getByHandle('event_recur');
            $recur = $ep->getCollectionAttributeValue($akrr);

            $allday = $ep->getAttribute('event_allday');

            $dates = $dth->translate_from($ep);
            $thru = $location = $ep->getAttribute('event_thru');
            if ($dates[1]['date']) {
                $from = $dates[1]['date'];
            } else {
                $from = $date_origin;
            }
            $to = date('Y-m-d', strtotime($thru));


            if ($recur == 'daily' && !in_array($id, $recured_array)) {

                array_push($recured_array, $id);

                if ($allday == 1) {
                    $allday_text = true;
                } else {
                    $allday_text = false;
                }
                $lastnode = null;
                if ($exclude[0]) {
                    $exfrom = null;
                    $ex = 0;
                    foreach ($exclude as $exend) {
                        if ($exend != date('Y-m-d', strtotime('+1 day', strtotime($lastnode))) || !$lastnode) {
                            if (date('Y-m-d', strtotime($exend)) > $date && date('Y-m-d', strtotime($exend)) < $date2) {
                                $exto = date('Y-m-d', strtotime('-1 day', strtotime($exend)));
                                if (!$exfrom) {
                                    $exfrom = $from;
                                }
                                $ex++;

                                $event_item = array(
                                    'id' => $id . '_' . $ex,
                                    'title' => $title,
                                    'allDay' => $allday_text,
                                    'start' => $exfrom . 'T' . date('H:i', strtotime($stime)) . ':00Z',
                                    'end' => $exto . 'T' . date('H:i', strtotime($etime)) . ':00Z',
                                    'color' => $color,
                                    'url' => $url,
                                    'description' => $content
                                );

                                $events_array['event'][] = $event_item;

                                $exfrom = date('Y-m-d', strtotime('+1 day', strtotime($exend)));

                                $lastnode = $exend;
                            }
                        } else {
                            $exfrom = date('Y-m-d', strtotime('+1 day', strtotime($exend)));
                            $lastnode = $exend;
                        }
                    }
                    if (!$exfrom) {
                        $exfrom = $from;
                    }
                    $event_item = array(
                        'id' => $id,
                        'title' => $title,
                        'allDay' => $allday_text,
                        'start' => $exfrom . 'T' . date('H:i', strtotime($stime)) . ':00Z',
                        'end' => $to . 'T' . date('H:i', strtotime($etime)) . ':00Z',
                        'color' => $color,
                        'url' => $url,
                        'description' => $content
                    );

                    $events_array['event'][] = $event_item;


                } else {
                    $event_item = array(
                        'id' => $id,
                        'title' => $title . ' - ' . $exclude[0],
                        'allDay' => $allday_text,
                        'start' => $from . 'T' . date('H:i', strtotime($stime)) . ':00Z',
                        'end' => $to . 'T' . date('H:i', strtotime($etime)) . ':00Z',
                        'color' => $color,
                        'url' => $url,
                        'description' => $content
                    );

                    $events_array['event'][] = $event_item;
                }

            } elseif (!in_array($id, $recured_array)) {

                if ($allday == 1) {
                    $allday_text = true;
                } else {
                    $allday_text = false;
                }

                $event_item = array(
                    'id' => $id,
                    'title' => $title,
                    'allDay' => $allday_text,
                    'start' => $date . 'T' . date('H:i', strtotime($stime)) . ':00Z',
                    'end' => $date . 'T' . date('H:i', strtotime($etime)) . ':00Z',
                    'color' => $color,
                    'url' => $url
                );

                if ($content) {
                    $event_item['description'] = $content;
                }

                $events_array['event'][] = $event_item;

            }
        }

        $encode = json_encode($events_array, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        //print $encode;
        $this->set('encode',$encode);
        //Loader::PackageElement('blocks/pro_event_list/calendar_jquery', 'proevents', array('encode' => $encode));
    }

}