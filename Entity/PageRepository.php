<?php

namespace Room13\PageBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Entities;

class PageRepository extends EntityRepository
{

    public function parsePath($path)
    {
        $path = strtolower($path);

        $path = array_filter(explode('/', $path), function($e)
        {
            return strlen(trim($e)) > 0;
        });

        //$path = array_reverse($path);

        return $path;

    }


    public function findOneByPath($path)
    {
        $path = trim($path);

        if(strrpos($path,'/')===strlen($path)-1)
        {
            $path = trim(substr($path,0,strrpos($path,'/')));
        }

        if(strlen($path)===0)
        {
            $path = '/';
        }

        $url = $this->getEntityManager()->getRepository('Room13PageBundle:Url')->findOneBy(array(
            'url'=>$path,
            'wildcard'=>false
        ));


        if(!$url)
        {
            // no page found yet, so try finding a wildcard page

            $parsedPath     = $this->parsePath($path);
            $wildcardPaths  = array();

            do
            {
                $wildcardPaths[] = '/'.implode('/',$parsedPath);
                array_pop($parsedPath);

            } while(count($parsedPath)>0);

            // no exact match found, try finding a wildcard page
            $q = $this->getEntityManager()->createQuery('
                SELECT u
                FROM Room13PageBundle:Url u
                WHERE u.wildcard = :wildcard
                AND u.url IN (:paths)
                ORDER BY u.url DESC
            ');

            $q->setParameter('wildcard',true);
            $q->setParameter('paths',$wildcardPaths);
            $q->setMaxResults(1);

            $url = $q->getOneOrNullResult();

        }

        if ($url)
        {
            return $url->getPage();
        }

        return null;

    }
}