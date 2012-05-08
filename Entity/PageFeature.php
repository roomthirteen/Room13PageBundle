<?php

namespace Room13\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Room13\PageBundle\Entity\PageFeature
 *
 * @ORM\Table(name="room13_page_feature")
 * @ORM\Entity
 */
class PageFeature
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
     * @var string $key
     *
     * @ORM\Column(name="descriptor", type="string", length=255)
     */
    private $descriptor;

    /**
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var text $content
     *
     * @ORM\Column(name="content", type="text", nullable=true)
     */
    private $content;


    /**
     *
     * @ORM\ManyToMany(targetEntity="Room13\PageBundle\Entity\Page", mappedBy="features", cascade={"ALL"})
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
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set content
     *
     * @param text $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return text
     */
    public function getContent()
    {
        return $this->content;
    }

    public function addPage(Page $page)
    {
        $this->pages[] = $page;
    }

    /**
     * @param string $descriptor
     */
    public function setDescriptor($descriptor)
    {
        $this->descriptor = $descriptor;
    }

    /**
     * @return string
     */
    public function getDescriptor()
    {
        return $this->descriptor;
    }
}