<?php

namespace Room13\PageBundle\Templating\Twig;

use Room13\PageBundle\Templating\Helper\PageHelper;
use Room13\PageBundle\Entity\Page;

class PageExtension extends \Twig_Extension
{

    /**
     * @var PageHelper
     */
    private $helper;


    public function __construct(PageHelper $helper)
    {
        $this->helper = $helper;
    }

    public function getGlobals()
    {
        return array(
            'current_page'=>$this->helper->getCurrentPage()
        );
    }

    public function filterPageDebug(Page $page)
    {
        return $this->helper->debugPage($page);
    }

    public function filterPageContent(Page $page)
    {
        return $this->helper->pageContent($page);
    }
    public function findPage($path)
    {
        return $this->helper->findPage($path);
    }

    public function getFilters()
    {
        return array(
            'page_debug'    => new \Twig_Filter_Method($this,'filterPageDebug',array('is_safe'=>array('html'))),
            'page_content'  => new \Twig_Filter_Method($this,'filterPageContent',array('is_safe'=>array('html'))),
            'page_find'  => new \Twig_Filter_Method($this,'findPage'),
        );
    }

    public function getName()
    {
        return 'room13_page';
    }
}