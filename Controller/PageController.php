<?php

namespace Room13\PageBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\DiExtraBundle\Annotation as DI;
 use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PageController extends Controller
{


    /**
     * @var \Doctrine\ORM\EntityManager
     * @DI\Inject("doctrine.orm.entity_manager")
     */
    private $em;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     * @DI\Inject("request")
     */
    private $request;

    /**
     * @Route("/page/translation/{pageId}", requirements={"pageId" = "\d+"})
     */
    public function getTranslations($pageId)
    {


        $page = $this->em->getRepository('Room13PageBundle:Page')->find($pageId);

        if($page==null)
        {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException('Page '.$pageId);
        }

        $translations = $this->em->getRepository('Gedmo\Translatable\Entity\Translation')->findTranslations($page);

        foreach($translations as $translation)
        {

        }

        $response = new Response(json_encode($translations),200,array(
            'Content-Type'  => 'application/json'
        ));

        return $response;
    }

    /**
    * @Route("/page/by-path")
    * @Template("BalkanrideFrontendBundle::page.html.twig")
    */
    public function pageByPathAction()
    {
        $path = $this->getRequest()->get('path','');
        $page = $this->em->getRepository('Room13PageBundle:Page')->findOneByPath('/'.$path);

        // set the page to the frontend twig extension so the lookup
        // will not be executed two times
        // TODO: bad dependenciy, think of another way to do this
        $twigExtension = $this->container->get('room13_page.templating.helper.page');
        $twigExtension->setCurrentPage($page);

        return array(
            'page'=>$page,
        );
    }

    /**
     * @Route("/page/{id}")
     * @Template("BalkanrideFrontendBundle::page.html.twig")
     */
    public function pageAction($id=null)
    {

        if($id===null)
        {
            $id = $this->getRequest()->getPathInfo();
        }

        $pageRepository = $this->em->getRepository('Room13PageBundle:Page');

        if(is_numeric($id))
        {
            // page id has been specified
            $page = $pageRepository->findOneById($id);

        }
        else
        {
            $page = $pageRepository->findOneByPath($id);
        }

        if($page===null)
        {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException(
                sprintf(
                    'Page with path "%s" not found',
                    $id
                )
            );
        }

        // set the page to the frontend twig extension so the lookup
        // will not be executed two times
        // TODO: bad dependenciy, think of another way to do this
        $twigExtension = $this->container->get('room13_page.templating.helper.page');
        $twigExtension->setCurrentPage($page);

        return array('page' => $page);
    }

}
