<?php

namespace Room13\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Room13\PageBundle\Entity\PageRepository")
 * @ORM\Table(name="room13_page_content")
 */
class ContentPage extends Page
{
    /**
     * @var string $title
     *
     * @ORM\Column(name="page_title", type="string", length=255, nullable = true)
     * @Gedmo\Translatable
     */
    private $pageTitle;

    /**
     * @var string $pageSubTitle
     *
     * @ORM\Column(name="page_subtitle", type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     */
    private $pageSubTitle;

    /**
     * @var string $navigationTitle;
     *
     * @ORM\Column(name="navigation_title", type="string", length=255, nullable=true)
     * @Gedmo\Translatable
     */
    private $navigationTitle;

    /**
     * @var text $content
     *
     * @ORM\Column(name="content", type="array", nullable=true)
     * @Gedmo\Translatable
     */
    private $content;


    /**
     * @var array
     * @ORM\Column(name="actions", type="array", nullable=true)
     */
    private $actions;

    public function __construct()
    {
        $this->content = array();
    }

    public function __toString()
    {
        return "wee";
    }


    /**
     * @param array $actions
     */
    public function setActions($actions)
    {
        $this->actions = $actions;
    }

    /**
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * @param \text $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return \text
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $navigationTitle
     */
    public function setNavigationTitle($navigationTitle)
    {
        $this->navigationTitle = $navigationTitle;
    }

    /**
     * @return string
     */
    public function getNavigationTitle()
    {
        return $this->navigationTitle;
    }

    /**
     * @param string $pageSubTitle
     */
    public function setPageSubTitle($pageSubTitle)
    {
        $this->pageSubTitle = $pageSubTitle;
    }

    /**
     * @return string
     */
    public function getPageSubTitle()
    {
        return $this->pageSubTitle;
    }

    /**
     * @param string $pageTitle
     */
    public function setPageTitle($pageTitle)
    {
        $this->pageTitle = $pageTitle;
    }

    /**
     * @return string
     */
    public function getPageTitle()
    {
        return $this->pageTitle;
    }

    public function getAttribute($name,$default=null)
    {
        foreach($this->attributes as $attribute)
        {
            if($attribute->getName()===$name)
            {
                return $attribute->getValue();
            }
        }

        return $default;
    }
}
