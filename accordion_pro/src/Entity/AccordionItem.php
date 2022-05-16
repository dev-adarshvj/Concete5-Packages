<?php

/**
 * @project:   Accordion Pro add-on for concrete5
 *
 * @author     Fabian Bitter
 * @copyright  (C) 2017 Bitter Webentwicklung (www.bitter-webentwicklung.de)
 * @version    1.0.0.5
 */

namespace Concrete\Package\AccordionPro\Src\Entity;

defined('C5_EXECUTE') or die('Access Denied.');

use Database;

/**
 * @Entity
 * @Table(name="AccordionItem")
 * */
class AccordionItem
{

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Column(type="string") * */
    protected $title = '';

    /**
     * @Column(type="string", length=16384) * */
    protected $paragraph = '';

    /**
     * @Column(type="integer") * */
    protected $blockTypeId = 0;

    /**
     * @Column(type="boolean") * */
    protected $isOpen = false;

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getParagraph()
    {
        return $this->paragraph;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function setParagraph($paragraph)
    {
        $this->paragraph = $paragraph;
    }

    public function getBlockTypeId()
    {
        return $this->blockTypeId;
    }

    public function setBlockTypeId($blockTypeId)
    {
        $this->blockTypeId = $blockTypeId;
    }
    
    public function getIsOpen()
    {
        return $this->isOpen;
    }

    public function setIsOpen($isOpen)
    {
        $this->isOpen = $isOpen;
    }

    public function remove()
    {
        $em = Database::connection()->getEntityManager();

        $em->remove($this);

        $em->flush();
    }

    public function save()
    {
        $em = Database::connection()->getEntityManager();

        $em->persist($this);

        $em->flush();
    }
}
