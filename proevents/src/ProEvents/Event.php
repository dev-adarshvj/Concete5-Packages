<?php  
namespace Concrete\Package\Proevents\Src\ProEvents;

use Loader;
use Page as ConcretePage;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;
use \Concrete\Core\Block\BlockType\BlockType as BlockType;
use \Concrete\Core\Attribute\Set as AttributeSet;
use \Concrete\Core\Page\Type\Composer\FormLayoutSet;
use \Concrete\Core\Page\Type\Composer\Control\Type\Type as PageTypeComposerControlType;
use \Concrete\Core\Page\Type\Composer\FormLayoutSetControl;

/**
 *
 * This getByID override is needed to bypass cache for pagination.
 * @package ProEvents
 *
 */
class Event extends ConcretePage
{


    /**
     * @param int $cID Collection ID of a page
     * @param string $versionOrig ACTIVE or RECENT
     * @param string $class
     * @return Page
     */
    public static function getByID($cID, $version = 'RECENT', $class = 'Page')
    {

        //cache removed for events
        /*
        $c = CacheLocal::getEntry('page', $cID . ':' . $version . ':' . $class);
        if ($c instanceof $class) {
            return $c;
        }
        */

        $where = "where Pages.cID = ?";
        $c = new $class;
        $c->populatePage($cID, $where, $version);
        
        // must use cID instead of c->getCollectionID() because cID may be the pointer to another page
        //CacheLocal::set('page', $cID . ':' . $version . ':' . $class, $c);

        return $c;
    }

    public static function saveData($p)
    {
        $db = Loader::db();

        $blocks = $p->getBlocks('Main');
        foreach ($blocks as $b) {
            if ($b->getBlockTypeHandle() == 'content' || $b->getBlockTypeHandle()=='core_page_type_composer_control_output') {
                $b->deleteBlock();
            }
        }

        $set = AttributeSet::getByHandle('proevent_additional_attributes');
        $setAttribs = $set->getAttributeKeys();
        if ($setAttribs) {
            foreach ($setAttribs as $ak) {
                $aksv = CollectionAttributeKey::getByHandle($ak->akHandle);
                $aksv->saveAttributeForm($p);
            }
        }

        $evt = CollectionAttributeKey::getByHandle('event_thru');
        $evt->saveAttributeForm($p);

        $cak = CollectionAttributeKey::getByHandle('event_tag');
        $cak->saveAttributeForm($p);

        $cck = CollectionAttributeKey::getByHandle('event_category');
        $cck->saveAttributeForm($p);

        $emdd = CollectionAttributeKey::getByHandle('event_multidate');
        $emdd->saveAttributeForm($p);

        $eexc = CollectionAttributeKey::getByHandle('event_exclude');
        $eexc->saveAttributeForm($p);

        $ead = CollectionAttributeKey::getByHandle('event_allday');
        $ead->saveAttributeForm($p);

        $eg = CollectionAttributeKey::getByHandle('event_grouped');
        $eg->saveAttributeForm($p);

        $evr = CollectionAttributeKey::getByHandle('event_recur');
        $evr->saveAttributeForm($p);

        $cnv = CollectionAttributeKey::getByHandle('exclude_nav');
        $cnv->saveAttributeForm($p);

        $ct = CollectionAttributeKey::getByHandle('thumbnail');
        $ct->saveAttributeForm($p);

        $cur = CollectionAttributeKey::getByHandle('event_local');
        $cur->saveAttributeForm($p);

        $cur = CollectionAttributeKey::getByHandle('address');
        $cur->saveAttributeForm($p);

        $cur = CollectionAttributeKey::getByHandle('contact_name');
        $cur->saveAttributeForm($p);

        $cur = CollectionAttributeKey::getByHandle('contact_email');
        $cur->saveAttributeForm($p);

        $cur = CollectionAttributeKey::getByHandle('event_price');
        $cur->saveAttributeForm($p);

        $qty = CollectionAttributeKey::getByHandle('event_qty');
        $qty->saveAttributeForm($p);

        $bt = BlockType::getByHandle('content');

        $request = \Request::getInstance();
        $data = array('content' => $request->get('eventBody'));

        $b = $p->addBlock($bt, 'Main', $data);
        $b->setCustomTemplate('event_post');

        $db = Loader::db();
        $pTemplate = $db->getOne("SELECT ptComposerFormLayoutSetControlID FROM PageTypeComposerOutputControls WHERE pTemplateID = ? AND ptID = ?",array($p->getPageTemplateID(),$p->getCollectionTypeID()));
        if($pTemplate){
            $db->Replace('PageTypeComposerOutputBlocks', array(
                                        'cID'=>$p->getCollectionID(),
                                        'arHandle'=>'Main',
                                        'cbDisplayOrder'=>0,
                                        'ptComposerFormLayoutSetControlID'=>$pTemplate,
                                        'bID'=>$b->getBlockID()
                                    ), 'cID', true);
        }

        $p->reindex();
    }

}
