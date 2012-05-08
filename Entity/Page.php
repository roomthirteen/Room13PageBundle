<?php

namespace Room13\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * Room13\PageBundle\Entity\Page
 *
 * @ORM\Entity(repositoryClass="Room13\PageBundle\Entity\PageRepository")
 * @ORM\Table(name="room13_page")
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
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     */
    protected $path;



    /**
     * @var \Doctrine\Common\Collections\Collection
    /**

     */
    protected $attributes;


    /**
     *
     * @ORM\ManyToMany(targetEntity="Room13\PageBundle\Entity\PageFeature", inversedBy="pages")
     * @ORM\JoinTable(name="room13_page_feature_mapping",
     *   joinColumns={@ORM\JoinColumn(name="page_id", referencedColumnName="id")},
     *   inverseJoinColumns={@ORM\JoinColumn(name="feature_id", referencedColumnName="id")}
     * )
     */
    private $features;

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
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id",onDelete="SET NULL")
     */
    protected $parent;

    /**
     * @ORM\OneToMany(targetEntity="Page", mappedBy="parent", cascade={"persist", "remove"})
     * @ORM\OrderBy({"tree_left" = "ASC"})
     */
    protected $children;

    /**
     * @ORM\OneToMany(targetEntity="Url", mappedBy="page", cascade={"persist", "remove"})
     */
    protected $urls;



    /**
     * @var \DateTime $created
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var \DateTime  $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updated;


    public function __construct()
    {
        $this->attributes = new \Doctrine\Common\Collections\ArrayCollection();
        $this->features   = new \Doctrine\Common\Collections\ArrayCollection();
        $this->urls       = new \Doctrine\Common\Collections\ArrayCollection();
        $this->content    = array();
    }

    public function __toString()
    {
        return $this->getTitle();
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


    public function getFullPath()
    {
        $curr = $this;
        $path = '';

        do
        {
            $path = '/'.$curr->path.$path;
            $curr = $curr->parent;
        }
        while ($curr!==null);

        return $path;
    }

    public function getPath()
    {
        $this->path;
    }

    public function setChildren($children)
    {
        $this->children = $children;
    }

    public function addChild(Page $child)
    {
        $this->children[] = $child;
    }

    /**
     * @return Page[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    public function hasChildren()
    {
        return $this->children!=null && count($this->children) > 0;
    }

    public function setUrls($urls)
    {
        $this->urls = $urls;
    }

    public function addUrl(Url $url)
    {
        $this->urls[] = $url;
    }

    public function getMainUrl()
    {
        return $this->urls[0]->getUrl();
    }

    public function getUrls()
    {
        return $this->urls;
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

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @param \DateTime $created
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    public function addFeature(PageFeature $feature)
    {
        $this->features[]=$feature;
    }

    /**
     * @param $type
     * @return bool
     */
    public function hasFeature($type)
    {
        foreach($this->features as $feature)
        {
            if($feature->getType()===$type)
            {
                return true;
            }
        }

        return false;
    }

   /**
    * @param $type string feature type
    * @return PageFeature|null
    */
    public function getFeature($type)
    {
        foreach($this->features as $feature)
        {
            if($feature->getType()===$type)
            {
                return $feature;
            }
        }

        return null;
    }


}