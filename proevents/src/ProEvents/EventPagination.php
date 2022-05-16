<?php  
namespace Concrete\Package\Proevents\Src\ProEvents;

use Concrete\Core\Search\ItemList\ItemList as AbstractItemList;
use Pagerfanta\Adapter\AdapterInterface;
use Pagerfanta\Pagerfanta;
use Core;
use Page;

class EventPagination extends Pagerfanta
{
    /** @var \Concrete\Core\Search\ItemList\ItemList */
    protected $list;

    public function __construct(AbstractItemList $itemList, AdapterInterface $adapter)
    {
        $this->list = $itemList;
        return parent::__construct($adapter);

    }

    public function getTotal()
    {
        return $this->getTotalResults();
    }

    public function getTotalResults()
    {
        return $this->getNbResults();
    }

    public function getTotalPages()
    {
        return $this->getNbPages();
    }

    /**
     * This is a convenience method that does the following: 1. it grabs the pagination/view service (which by default
     * is bootstrap 3) 2. it sets up URLs to start with the pass of the current page, and 3. it uses the default
     * item list query string parameter for paging. If you need more custom functionality you should consider
     * using the Pagerfanta\View\ViewInterface objects directly.
     * @return string
     */
    public function renderDefaultView($c)
    {
        $v = Core::make('pagination/view');
        $request = \Request::getInstance();
        if($c && !is_object($c)){
            $url = $c;
            $ajax = "<script>$('.pagination a').click(function(e){e.preventDefault();getEventResults($(this).attr('href'));return false;})";
        }else{
            if(!$c){$c = Page::getCurrentPage();}
            if(!$c){$c = Page::getByID($request->get('ccID'));}
            $url = $c->getCollectionLink();
        }
        $list = $this->list;
        $html = $v->render(
            $this,
            function ($page) use ($list, $url) {
                $qs = Core::make('helper/url');
                $url = $qs->setVariable($list->getQueryPaginationPageParameter(), $page, $url);
                return $url;
            }
        );


        return $html.$ajax;
    }

    public function getCurrentEventResults()
    {
        $this->list->debugStart();

        $results = parent::getCurrentPageResults();

        $this->list->debugStop();

        $ereturn = array();

        foreach ($results as $result) {
            $r = $this->list->getEventResult($result);

            if ($r != null) {
                $ereturn[] = $r;
            }
        }

        return $ereturn;
    }

} 