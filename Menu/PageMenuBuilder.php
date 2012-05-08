<?php

namespace Room13\PageBundle\Menu;

use Doctrine\ORM\EntityManager;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Room13\PageBundle\Entity\Page;
use Knp\Menu\ItemInterface;

class PageMenuBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(FactoryInterface $factory, EntityManager $em)
    {
        $this->factory = $factory;
        $this->em = $em;
    }

    public function createPageMenu(Request $request, $paths, $depth)
    {

        if(!is_array($paths))
        {
            $paths = array($paths);
        }

        $menu = $this->factory->createItem('root');
        $menu->setCurrentUri($request->getPathInfo());

        foreach($paths as $path)
        {
            $page = $this->em->getRepository('Room13PageBundle:Page')->findOneByPath($path);
            $this->buildMenu($menu,$page,$depth);
        }



        return $menu;

    }

    private function buildMenu(ItemInterface $menu,Page $page,$depth)
    {
        $item = $menu->addChild($page->getTitle());

        foreach($page->getChildren() as $childPage)
        {
            $child = $item->addChild($childPage->getTitle());
            $child->setUri($childPage->getMainUrl());
        }


    }
}