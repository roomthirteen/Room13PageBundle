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
    protected $id;

    /**
     * @var string $url
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    protected $url;



    /**
     * @var boolean $wildcard
     *
     * @ORM\Column(name="wildcard", type="boolean", nullable=false)
     */
    protected $wildcard;


    /**
     * @var Page
     *
     * @ORM\ManyToOne(targetEntity="Room13\PageBundle\Entity\Page",inversedBy="urls", cascade={"persist","merge"})
     */
    protected $page;

    public function __construct($page=null)
    {
        $this->page = $page;
        $this->url = null;
        $this->wildcard = false;

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

    /**
     * @param boolean $wildcard
     */
    public function setWildcard($wildcard)
    {
        $this->wildcard = $wildcard;
    }

    /**
     * @return boolean
     */
    public function getWildcard()
    {
        return $this->wildcard;
    }
}