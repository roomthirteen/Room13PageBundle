<?php

namespace Room13\PageBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Entities;

class PageRepository extends EntityRepository
{

    public function parsePath($path)
    {
        $path = strtolower($path);

        $path = array_filter(explode('/',$path),function($e){
            return strlen(trim($e))>0;
        });

        $path = array_reverse($path);

        return $path;

    }


    public function findOneByPath($path){

        /**
         * @var \Doctrine\ORM\QueryBuilder
         */
        $qb  = $this->createQueryBuilder('p');

        $path = trim($path);

        if($path==='/')
        {
            // TODO: find a configurable solution, di not available here
            $path = '/home';
        }


        $qb
            ->select('p')
            //->from('Room13PageBundle:Page','p')
            ->where($qb->expr()->andX(
                $qb->expr()->eq('p.path','?1'),
                $qb->expr()->eq('p.tree_level','?2')
            ))
        ;

        $pathInfo = $this->parsePath($path);
        $page     = null;
        $level    = 0;


        do
        {
            $pathElem = array_pop($pathInfo);

            $qb
                ->setParameter(1,$pathElem)
                ->setParameter(2,$level)
                ->getQuery();

            $page = $qb->getQuery()->getOneOrNullResult();

            if($page===null)
            {
                // a page in the path has not been found, so stop looking
                break;
            }

            if($page instanceof AliasPage)
            {
            //    echo "\nderefferencing alias\n";
            //    $page = $page->getReferencedPage();
            }

            $level++;

        } while(count($pathInfo)>0);

        return $page;

    }
}