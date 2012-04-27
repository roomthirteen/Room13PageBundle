<?php

namespace Room13\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * Room13\PageBundle\Entity\Page
 *
 * @ORM\Entity(repositoryClass="Room13\PageBundle\Entity\PageRepository")
 * @ORM\Table(name="room13_page")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"content" = "ContentPage", "alias" = "AliasPage"})
 * @Gedmo\Tree(type="nested")
 */
class Page
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
     * @var string $title
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    protected $title;


    /**
     * @var string $path
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    protected $path;


    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(
     *      targetEntity="Room13\PageBundle\Entity\PageAttribute",
     *      inversedBy="pages",
     *      cascade={"persist","merge","detatch"}
     * )
     * @ORM\JoinTable(
     *      name="balkanride_page_attributes",
     *      joinColumns={
     *          @ORM\JoinColumn(
     *              name="page_id",
     *              referencedColumnName="id"
     *          )
     *      },
     *      inverseJoinColumns={
     *          @ORM\JoinColumn(
     *              name="page_attribute_id",
     *              referencedColumnName="id"
     *          )
     *      }
     * )
     */
    protected $attributes;

    /**
     * @var string

     */
    protected $type;

    /**
     * @var string $slug
     *
     * @Gedmo\Slug(fields={"path"})
     * @ORM\Column(name="slug", type="string", length=255)
     * @Gedmo\TreePathSource
     */
    protected $slug;

    /**
     * @Gedmo\TreeRoot
     * @ORM\Column(name="tree_root", type="integer", nullable=true)
     */
    protected $tree_root;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="tree_left", type="integer", nullable=true)
     */
    protected $tree_left;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="tree_right", type="integer", nullable=true)
     */
    protected $tree_right;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="tree_level", type="integer", nullable=true)
     */
    protected $tree_level;

    /**
     * @var Page
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="Page", inversedBy="children", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Page", mappedBy="parent", cascade={"persist", "remove"})
     * @ORM\OrderBy({"tree_left" = "ASC"})
     */
    protected $children;




    public function __construct()
    {
        $this->attributes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->getPath();
    }

    public function getPath()
    {
        $this->path;
    }

    public function setChildren($children)
    {
        $this->children = $children;
    }

    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param PageAttribute $att
     */
    public function addAttribute(PageAttribute $att)
    {
        $this->attributes[]=$att;
    }

    /**
     * @param PageAttribute $att
     */
    public function removeAttribute(PageAttribute $att)
    {
        $this->attributes->remove($att);
    }

    /**
     * @return PageAttribute[]
     */
    public function getAttributes()
    {
        return $this->attributes;
    }


    /**
     * @param \Room13\PageBundle\Entity\Page $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return \Room13\PageBundle\Entity\Page
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    public function setTreeLeft($tree_left)
    {
        $this->tree_left = $tree_left;
    }

    public function getTreeLeft()
    {
        return $this->tree_left;
    }

    public function setTreeLevel($tree_level)
    {
        $this->tree_level = $tree_level;
    }

    public function getTreeLevel()
    {
        return $this->tree_level;
    }

    public function setTreeRight($tree_right)
    {
        $this->tree_right = $tree_right;
    }

    public function getTreeRight()
    {
        return $this->tree_right;
    }

    public function setTreeRoot($tree_root)
    {
        $this->tree_root = $tree_root;
    }

    public function getTreeRoot()
    {
        return $this->tree_root;
    }

    public function getType()
    {
        if($this instanceof ContentPage)
        {
            return 'content';
        }
        elseif($this instanceof AliasPage)
        {
            return 'alias';
        }
        elseif($this instanceof NullPage)
        {
            return 'null';
        }

        throw new \Exception("Unsupported type class ".get_class($this));
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }


}