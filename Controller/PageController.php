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
    * @Route("/page/by-path")
    */
    public function pageByPathAction()
    {
        $path = $this->getRequest()->get('path','');
        $page = $this->em->getRepository('Room13PageBundle:Page')->findOneByPath('/'.$path);

        return $this->processPage($page);
    }

    /**
     * @Route("/page/{id}")
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

        return $this->processPage($page);

    }


    private function processPage($page)
    {
        // set the page to the frontend twig extension so the lookup
        // will not be executed two times
        // TODO: bad dependenciy, think of another way to do this
        $twigExtension = $this->container->get('room13_page.templating.helper.page');
        $twigExtension->setCurrentPage($page);


        $tpl = $this->getRequest()->isXmlHttpRequest() ? $this->container->getParameter('room13.page.template.page_ajax') : $this->container->getParameter('room13.page.template.page_full');

        return $this->render($tpl, array(
            'page' => $page
        ));
    }

}
