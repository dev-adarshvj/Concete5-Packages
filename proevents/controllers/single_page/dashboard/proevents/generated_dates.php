<?php  
namespace Concrete\Package\Proevents\Controller\SinglePage\Dashboard\Proevents;

use \Concrete\Core\Page\Controller\DashboardPageController;
use \Symfony\Component\HttpFoundation\Session\Session as SymfonySession;
use Loader;
use View;

class GeneratedDates extends DashboardPageController
{

    public $helpers = array('html', 'form');

    public function view()
    {
        $session = new SymfonySession();
        //$session->start();
        $eventify = new \Concrete\Package\Proevents\Controller\Helpers\Eventify;
        if ($this->request('search')) {
            $session->set('generated_dates.search', $this->request('search'));
        }
        if ($this->request('search_type')) {
            $session->set('generated_dates.search_type', $this->request('search_type'));
        }

        if ($session->get('generated_dates.search')) {
            $this->set('fullDateList', $eventify->getSearchEvents($session->get('generated_dates.search'), $session->get('generated_dates.search_type')));
        } else {
            $this->set('fullDateList', $eventify->getAllEvents());
        }

        $this->set('controller', $this);
        $this->set('searchword', $session->get('generated_dates.search'));
        $this->set('searchtype', $session->get('generated_dates.search_type'));
    }

    public function clear_search()
    {
        $session = new SymfonySession();
        //$session->start();
        $session->remove('generated_dates.search');
        $session->remove('generated_dates.search_type');
        $this->view();
    }

    public function date_edit()
    {

        $eID = $this->request('eID');
        $title = $this->request('title');
        $status = $this->request('status');
        $event_price = $this->request('event_price') ? $this->request('event_price') : null;
        $event_qty = $this->request('event_qty') ? $this->request('event_qty') : null;
        $description = $this->translateTo(str_replace('\\', '', $this->request('description')));

        $vars = array($title, $description, $status, $event_price, $event_qty, $eID);

        $eventify = new \Concrete\Package\Proevents\Helpers\Eventify;
        $eventify->updateDate($vars);

        $this->redirect('/dashboard/proevents/generated_dates/updated/');
    }

    public function updated()
    {
        $this->set('message', t('Date succesfully updated!'));
        $this->view();
    }

    public function delete_check($eID, $name = null)
    {
        $this->set('remove_name', $name);
        $this->set('remove_eid', $eID);
        $this->view();
    }

    public function delete($eID, $name)
    {
        $db = Loader::db();
        $db->Execute("DELETE from btProEventDates where eID = '$eID'");
        $this->set(
            'message',
            t(
                '"' . $name . '" has been deleted. Remember to edit your event and add this date to the "exclude dates" section.'
            )
        );
        $this->set('remove_name', '');
        $this->set('remove_eid', '');
        $this->view();
    }

    public function clear_warning()
    {
        $this->set('remove_name', '');
        $this->set('remove_eid', '');
        $this->view();
    }

    function translateTo($text)
    {
        // keep links valid
        $url1 = str_replace('/', '\/', BASE_URL . DIR_REL . '/' . DISPATCHER_FILENAME);
        $url2 = str_replace('/', '\/', BASE_URL . DIR_REL);
        $url3 = View::url('/download_file', 'view_inline');
        $url3 = str_replace('/', '\/', $url3);
        $url3 = str_replace('-', '\-', $url3);
        $url4 = View::url('/download_file', 'view');
        $url4 = str_replace('/', '\/', $url4);
        $url4 = str_replace('-', '\-', $url4);
        $text = preg_replace(
            array(
                '/' . $url1 . '\?cID=([0-9]+)/i',
                '/' . $url3 . '([0-9]+)\//i',
                '/' . $url4 . '([0-9]+)\//i',
                '/' . $url2 . '/i'
            ),
            array(
                '{CCM:CID_\\1}',
                '{CCM:FID_\\1}',
                '{CCM:FID_DL_\\1}',
                '{CCM:BASE_URL}'
            ),
            $text
        );
        return $text;
    }

    function translateFrom($text)
    {
        // old stuff. Can remove in a later version.
        $text = str_replace('href="{[CCM:BASE_URL]}', 'href="' . BASE_URL . DIR_REL, $text);
        $text = str_replace('src="{[CCM:REL_DIR_FILES_UPLOADED]}', 'src="' . BASE_URL . REL_DIR_FILES_UPLOADED, $text);

        // we have the second one below with the backslash due to a screwup in the
        // 5.1 release. Can remove in a later version.

        $text = preg_replace(
            array(
                '/{\[CCM:BASE_URL\]}/i',
                '/{CCM:BASE_URL}/i'
            ),
            array(
                BASE_URL . DIR_REL,
                BASE_URL . DIR_REL
            ),
            $text
        );

        // now we add in support for the links

        $text = preg_replace_callback(
            '/{CCM:CID_([0-9]+)}/i',
            array('DashboardProeventsGeneratedDatesController', 'replaceCollectionID'),
            $text
        );

        $text = preg_replace_callback(
            '/<img [^>]*src\s*=\s*"{CCM:FID_([0-9]+)}"[^>]*>/i',
            array('DashboardProeventsGeneratedDatesController', 'replaceImageID'),
            $text
        );

        // now we add in support for the files that we view inline
        $text = preg_replace_callback(
            '/{CCM:FID_([0-9]+)}/i',
            array('DashboardProeventsGeneratedDatesController', 'replaceFileID'),
            $text
        );

        // now files we download

        $text = preg_replace_callback(
            '/{CCM:FID_DL_([0-9]+)}/i',
            array('DashboardProeventsGeneratedDatesController', 'replaceDownloadFileID'),
            $text
        );

        return $text;
    }

    private function replaceFileID($match)
    {
        $fID = $match[1];
        if ($fID > 0) {
            $path = File::getRelativePathFromID($fID);
            return $path;
        }
    }

    private function replaceImageID($match)
    {
        $fID = $match[1];
        if ($fID > 0) {
            preg_match('/width\s*="([0-9]+)"/', $match[0], $matchWidth);
            preg_match('/height\s*="([0-9]+)"/', $match[0], $matchHeight);
            $file = File::getByID($fID);
            if (is_object($file) && (!$file->isError())) {
                $imgHelper = Loader::helper('image');
                $maxWidth = ($matchWidth[1]) ? $matchWidth[1] : $file->getAttribute('width');
                $maxHeight = ($matchHeight[1]) ? $matchHeight[1] : $file->getAttribute('height');
                if ($file->getAttribute('width') > $maxWidth || $file->getAttribute('height') > $maxHeight) {
                    $thumb = $imgHelper->getThumbnail($file, $maxWidth, $maxHeight);
                    return preg_replace('/{CCM:FID_([0-9]+)}/i', $thumb->src, $match[0]);
                }
            }
            return $match[0];
        }
    }

    private function replaceDownloadFileID($match)
    {
        $fID = $match[1];
        if ($fID > 0) {
            $c = Page::getCurrentPage();
            if (is_object($c)) {
                return View::url('/download_file', 'view', $fID, $c->getCollectionID());
            } else {
                return View::url('/download_file', 'view', $fID);
            }
        }
    }

    private function replaceCollectionID($match)
    {
        $cID = $match[1];
        if ($cID > 0) {
            $c = Page::getByID($cID, 'ACTIVE');
            return Loader::helper("navigation")->getLinkToCollection($c);
        }
    }
}