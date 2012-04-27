<?php

namespace Room13\PageBundle\Twig;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\Container;

use Room13\PageBundle\Entity\Page;
use Room13\PageBundle\Entity\ContentPage;
use Room13\PageBundle\Entity\AliasPage;
use Room13\PageBundle\Entity\NullPage;

class PageExtension extends \Twig_Extension
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Container
     */
    private $container;

    /**
     * @var \Room13\PageBundle\Entity\Page
     */
    private $currentPage;

    private $identifierCount = array();


    public function __construct(EntityManager $em,Container $container)
    {
        $this->currentPage = false;
        $this->em = $em;
        $this->container = $container;
    }

    public function setCurrentPage(Page $page)
    {
        $this->currentPage = $page;
    }

    private function getCurrentPage()
    {
        if($this->currentPage!==false && $this->currentPage !== null)
        {
            return $this->currentPage;
        }

        $request = $this->container->get('request');
        $path    = $request->getPathInfo();

        if($this->currentPage===false)
        {
            $this->currentPage = $this->em->getRepository('Room13PageBundle:Page')->findOneByPath($path);
        }

        if($this->currentPage === null)
        {
            // create a dummy object for current page so error checking in templates can be ommited
            $this->currentPage = new NullPage($path);
        }

        return $this->currentPage;

    }

    public function getGlobals()
    {
        return array(
            'current_page'=>$this->getCurrentPage()
        );
    }

    public function pageContent(Page $page)
    {
        if($page->getProcessContent())
        {
            $layout = $page->getAttribute('layout');
            $data   = unserialize($page->getContent());

            if($layout===null)
            {
                throw new \InvalidArgumentException(sprintf(
                    'Page "%s" is set to have it\'s content processed but does not have a layout',
                    $page->getId()
                ));
            }

            if(!is_array($data))
            {
                throw new \InvalidArgumentException(sprintf(
                    'Page "%s" is set to have it\'s content processed but content is not a serialized array',
                    $page->getId()
                ));
            }

            $search     = array();
            $replace    = array();

            foreach($data as $key=>$value)
            {
                $search[]   ='{{'.$key.'}}';
                $replace[]  =$value;
            }

            $layout = str_replace($search,$replace,$layout);

            return $layout;
        }
        else
        {
            return $page->getContent();
        }
    }

    public function debugPage(Page $page)
    {

        $attribs = array();

        $attribs['Type']=get_class($page);
        $attribs['Path']=$page->getPath();

        if($page instanceof ContentPage)
        {
            $attribs['Id']=$page->getId();
            $attribs['Title']=$page->getTitle();
            $attribs['Pagetitle']=$page->getPageTitle();
            $attribs['Pagesubtitle']=$page->getPageSubtitle();
        }
        elseif($page instanceof AliasPage)
        {
            $alias = $page->getReferencedPage();

            $attribs['Id']=$page->getId();
            $attribs['Title']=$page->getTitle();
            $attribs['Aliased Page']='
            <table style="margin-left: 20px; border-left: solid 5px lightgrey;">
                <tr><td><strong>Id</strong></td><td>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp; '.$alias->getId().'</td></tr>
                <tr><td><strong>Title</strong></td><td>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp; '.$alias->getTitle().'</td></tr>
                <tr><td><strong>Pagetitle</strong></td><td>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp; '.$alias->getPageTitle().'</td></tr>
                <tr><td><strong>Pagesubtitle</strong></td><td>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp; '.$alias->getPageSubtitle().'</td></tr>
            </table>
            ';
        }

        $content = '';

        if(count($page->getAttributes())>0)
        {

            foreach($page->getAttributes() as $pageAttrib)
            {
                $attribs['Attribute-'.$pageAttrib->getName()]=htmlentities($pageAttrib->getValue());
            }

        }

        foreach($attribs as $name=>$val)
        {
            $content.="<tr><td style='vertical-align: top;'><strong>{$name}</strong> </td><td>&nbsp;&nbsp;&nbsp;:&nbsp;&nbsp; {$val}</td></tr>";
        }

        echo "
            <div class='well'>
                <h2>Page debug</h2>
                <table>
                  {$content}
                </table>
            </div>
        ";
    }


    public function getFilters()
    {
        return array(
            'page_debug'    => new \Twig_Filter_Method($this,'debugPage',array('is_safe'=>array('html'))),
            'page_content'  => new \Twig_Filter_Method($this,'pageContent',array('is_safe'=>array('html'))),
        );
    }

    public function getName()
    {
        return 'balkanride_page';
    }
}