<?php

namespace Room13\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Room13\PageBundle\Entity\PageAttribute
 *
 * @ORM\Table(name="room13_page_attribute")
 * @ORM\Entity
 */
class PageAttribute
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var text $value
     *
     * @ORM\Column(name="value", type="text")
     */
    private $value;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Room13\PageBundle\Entity\Page", mappedBy="attributes")
     */
    private $pages;

    function __construct()
    {
        $this->pages = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set value
     *
     * @param text $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get value
     *
     * @return text 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $pages
     */
    public function setPages($pages)
    {
        $this->pages = $pages;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getPages()
    {
        return $this->pages;
    }

    public function addPage(Page $page)
    {
        $this->pages[]=$page;
    }

    public function removePage(Page $page)
    {
        $this->pages->remove($page);
    }


}