<?php

/**
 * @project:   Accordion Pro add-on for concrete5
 *
 * @author     Fabian Bitter
 * @copyright  (C) 2017 Bitter Webentwicklung (www.bitter-webentwicklung.de)
 * @version    1.0.0.5
 */

namespace Concrete\Package\AccordionPro\Src;

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Package\AccordionPro\Src\Entity\AccordionItem;
use Database;

class AccordionItems
{
    private static $instance = null;
    private $em;

    /**
     * @return AccordionItems
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function __construct()
    {
        $this->em = Database::connection()->getEntityManager();
    }
    
    /**
     *
     * @param integer $blockTypeId
     *
     * @return array
     */
    public function getItemsAsArray($blockTypeId)
    {
        $arrItems = array();
        
        $items = $this->getItems($blockTypeId);
        
        if (is_array($items)) {
            foreach ($items as $item) {
                $arrItem = array();
                
                $arrItem["title"] = $item->getTitle();
                $arrItem["paragraph"] = $item->getParagraph();
                $arrItem["isOpen"] = $item->getIsOpen() === true ? 1 : 0;
                
                array_push($arrItems, $arrItem);
            }
        }
        
        return $arrItems;
    }

    /**
     * @param integer $blockTypeId
     *
     * @return Array
     */
    public function getItems($blockTypeId)
    {
        return $this->em->
                        getRepository('Concrete\Package\AccordionPro\Src\Entity\AccordionItem')->
                        findBy(array("blockTypeId" => $blockTypeId));
    }

    /**
     * @param integer $blockTypeId
     * @param array $arrItems
     */
    public function setItems($blockTypeId, $arrItems)
    {
        $this->removeItems($blockTypeId);
        $this->addItems($blockTypeId, $arrItems);
    }

    /**
     * @param integer $oldBlockTypeId
     * @param integer $newBlockTypeId
     */
    public function duplicateItems($oldBlockTypeId, $newBlockTypeId)
    {
        $arrItems = $this->getItemsAsArray($oldBlockTypeId);
        
        $this->addItems($newBlockTypeId, $arrItems);
    }

    /**
     * @param integer $blockTypeId
     */
    public function removeItems($blockTypeId)
    {
        $this->em->createQueryBuilder()
                ->delete('Concrete\Package\AccordionPro\Src\Entity\AccordionItem', 'i')
                ->where("i.blockTypeId = :blockTypeId")
                ->setParameter(':blockTypeId', $blockTypeId)
                ->getQuery()
                ->execute();
    }

    /**
     * @param integer $blockTypeId
     * @param array $arrItem
     */
    private function addItem($blockTypeId, $arrItem)
    {
        $entity = $this->arrayToEntity($arrItem);

        $entity->setBlockTypeId($blockTypeId);

        $this->em->persist($entity);

        $this->em->flush();
    }

    /**
     *
     * @param array $arrItem
     *
     * @return AccordionItem
     */
    private function arrayToEntity($arrItem)
    {
        $entity = new AccordionItem;

        if (isset($arrItem["title"])) {
            $entity->setTitle($arrItem["title"]);
        }

        if (isset($arrItem["paragraph"])) {
            $entity->setParagraph($arrItem["paragraph"]);
        }

        if (isset($arrItem["isOpen"])) {
            $entity->setIsOpen(intval($arrItem["isOpen"]) === 1);
        }
        
        return $entity;
    }

    /**
     * @param integer $blockTypeId
     * @param array $arrItems
     */
    private function addItems($blockTypeId, $arrItems)
    {
        if (is_array($arrItems)) {
            foreach ($arrItems as $arrItem) {
                $this->addItem($blockTypeId, $arrItem);
            }
        }
    }
}
