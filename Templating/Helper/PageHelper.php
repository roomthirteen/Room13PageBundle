<?php

namespace Room13\PageBundle\Templating\Helper;

use Symfony\Component\DependencyInjection\Container;

use Room13\PageBundle\Entity\Page;
use Room13\PageBundle\Entity\NullPage;
use Room13\PageBundle\Entity\AliasPage;
use Room13\PageBundle\Entity\ContentPage;

use Balkanride\MainBundle\Entity\User;

class PageHelper
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var Page
     */
    private $currentPage;

    /**
     * @var array Array to store id generator sequence for different namespaces
     */
    private $identifierCount = array();

    function __construct(Container $container)
    {
        $this->container = $container;
        $this->currentPage = false;
    }

    public function getPageRepository()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        return $em->getRepository('Room13PageBundle:Page');
    }

    public function setCurrentPage(Page $page)
    {
        $this->currentPage = $page;
    }

    public function getCurrentPage()
    {
        if($this->currentPage!==false && $this->currentPage !== null)
        {
            return $this->currentPage;
        }

        $request = $this->container->get('request');
        $path    = $request->getPathInfo();

        if($this->currentPage===false)
        {
            $this->currentPage = $this->getPageRepository()->findOneByPath($path);
        }

        if($this->currentPage === null)
        {
            // create a dummy object for current page so error checking in templates can be ommited
            $this->currentPage = new NullPage($path);
        }

        return $this->currentPage;

    }

    public function findPage($path)
    {
        return $this->getPageRepository()->findOneByPath($path);
    }

    public function pageContent(Page $page)
    {
        $content =  $page->getContent();

        if(!$content)
        {
            return '';
        }

        if($page->hasFeature('layout'))
        {
            if(!is_array($content))
            {
                throw new \InvalidArgumentException(sprintf(
                    'Page with id "%s" has a layout but no array content',
                    $page->getId()
                ));
            }

            $layoutFeature  = $page->getFeature('layout');
            $layout         = $layoutFeature->getContent();
            $search         = array();
            $replace        = array();

            foreach($content as $key=>$value)
            {
                $search[]   ='{{'.$key.'}}';
                $replace[]  =$value;
            }

            $content = str_replace($search,$replace,$layout);


        }


        if(is_array($content))
        {
            $content = implode('',$content);
        }


        return $content;

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



}
