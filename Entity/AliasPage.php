<?php

namespace Room13\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="Room13\PageBundle\Entity\PageRepository")
 * @ORM\Table(name="room13_page_alias")
 */
class AliasPage extends Page
{

    /**
     * @var Page
     * @ORM\ManyToOne(targetEntity="ContentPage", cascade={"all"}, fetch="EAGER")
     * @ORM\JoinColumn(name="referenced_page_id", referencedColumnName="id")
     */
    private $referencedPage;


    /**
     * @param \Room13\PageBundle\Entity\Page $referencedPage
     */
    public function setReferencedPage($referencedPage)
    {
        if($referencedPage instanceof AliasPage)
        {
            throw new \InvalidArgumentException('An alias page may not reference another alias page.');
        }

        $this->referencedPage = $referencedPage;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getType()
    {
        return 'alias';
    }


    /**
     * @return \Room13\PageBundle\Entity\Page
     */
    public function getReferencedPage()
    {
        return $this->referencedPage;
    }

    public function __get($name)
    {
        $name = 'get'.ucfirst($name);
        return $this->referencedPage->$name();
    }

    public function __isset($name)
    {
        return method_exists($this->referencedPage,'get'.ucfirst($name));
    }

}
