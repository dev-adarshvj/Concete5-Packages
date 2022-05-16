<?php  
defined('C5_EXECUTE') or die(_("Access Denied."));

use Page as Page;
use Block as Block;
use \Concrete\Package\Proevents\Src\ProEvents\EventItem;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;

$site = 'http://' . $_SERVER["SERVER_NAME"];


function rssInfo($bID)
{
    $request = \Request::getInstance();
    $b = Block::getByID($bID);

    $ordering = $request->get('ordering');
    $num = $b->num;
    $rssInfo = '<title>' . $b->rssTitle . '</title>';
    $rssInfo .= '<link>' . BASE_URL . DIR_REL . '</link>';
    $rssInfo .= '<description>' . $b->rssDescription . '</description>';

    echo $rssInfo;

    getFeed($b);

}


function getFeed($b)
{

    $strip = array("&nbsp;", "&");
    $rplace = array(" ", "and");

    $controller = $b->getController();

    $controller->isPaged = false;

    $events = $controller->getEvents();
    foreach ($events as $date_string => $event) {

        $dh = Loader::helper('form/datetimetime');
        $date_array = $dh->translate_from_string($event->multidate);

        $date = $date_array['date'];
        $start = $date_array['start'];
        $end = $date_array['end'];
        $allday = $event->getAttribute('event_allday');
        $title = $event->getCollectionName();
        $block = $event->getBlocks('Main');
        $content = $event->getCollectionDescription();

        $feed .= '<item>';
        $feed .= '<title>' . $title . '</title>';
        $feed .= '<pubDate>' . date(DATE_RFC822, strtotime($date . ' ' . $start)) . '</pubDate>';
        $feed .= '<link>' . BASE_URL . DIR_REL . Loader::helper('navigation')->getLinkToCollection($event) . '</link>';
        $feed .= '<description><![CDATA[' . $content . ']]></description>';
        $feed .= '</item>';
        $feed = str_replace($strip, $rplace, $feed);
    }
    echo $feed;
}

header('Content-type: text/xml');
?>

<rss version="2.0">
    <channel>
        <?php  
        rssInfo($_GET['bID']);

        ?>
    </channel>
</rss>