<?php

namespace Room13\PageBundle\Menu;

use Doctrine\ORM\EntityManager;

use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Room13\PageBundle\Entity\Page;
use Knp\Menu\ItemInterface;

use Room13\TwigExtensionsBundle\Exception\InvalidArgumentException;

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

    private $defaultOptions = array(
        'depth'=>1,
        'title_property'=>'title',
        'root_as_child'=>true,
    );

    public function __construct(FactoryInterface $factory, EntityManager $em)
    {
        $this->factory = $factory;
        $this->em = $em;
    }


    public function createPageMenu(Request $request, $paths, array $options=array())
    {

        if(is_string($paths))
        {
            // got a single string, marshal it as array
            $paths = array($paths);
        }

        if(!is_array($paths))
        {
            throw new InvalidArgumentException(
                'Paths for menu pages invalid. Please specify an array of paths of a single path to build the menu from.'
            );
        }

        $options = array_merge($this->defaultOptions,$options);


        $menu = $this
            ->factory->createItem('root')
            ->setCurrentUri($request->getPathInfo())
        ;

        foreach($paths as $path)
        {
            $page = $this->em->getRepository('Room13PageBundle:Page')->findOneByPath($path);

            if($page === null)
            {
                throw new InvalidArgumentException(sprintf(
                    'Menu page with path "%s" not found',
                    $path
                ));
            }

            $this->buildMenu($menu,$page,$options);
        }



        return $menu;

    }

    private function buildMenu(ItemInterface $rootMenu,Page $page,array $options)
    {
        $titleGetter = 'get'.ucfirst($options['title_property']);

        $menu = $rootMenu
            ->addChild($page->$titleGetter())
            ->setAttribute('id',$page->getId())
        ;

        if($options['root_as_child'])
        {
            $menu
                ->addChild($page->$titleGetter())
                ->setUri($page->getMainUrl())
                ->setAttribute('id',$page->getId())
            ;
        }

        foreach($page->getChildren() as $childPage)
        {
            $menu
                ->addChild($childPage->$titleGetter())
                ->setUri($childPage->getMainUrl())
                ->setAttribute('id',$childPage->getId())
            ;
        }


    }
}