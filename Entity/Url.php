<?php

namespace Room13\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Room13\PageBundle\Entity\Url
 *
 * @ORM\Table(name="room13_page_url")
 * @ORM\Entity
 */
class Url
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
     * @var string $url
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /**
     * @var Page
     *
     * @ORM\ManyToOne(targetEntity="Room13\PageBundle\Entity\Page",inversedBy="urls", cascade={"persist","merge"})
     */
    private $page;

    public function __construct($page=null)
    {
        $this->page = $page;

        if($page!=null)
        {
            $this->url = $page->getFullPath();
        }
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
     * Set url
     *
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set page
     *
     * @param object $page
     */
    public function setPage($page)
    {
        $this->page = $page;
    }

    /**
     * Get page
     *
     * @return object 
     */
    public function getPage()
    {
        return $this->page;
    }
}