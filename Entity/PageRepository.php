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

        $path = array_reverse($path);

        return $path;

    }


    public function findOneByPath($path)
    {
        $path = trim($path);

        if($path==='/')
        {
            $path = '/home';
        }

        if(strrpos($path,'/')===strlen($path)-1)
        {
            $path = substr($path,0,strrpos($path,'/'));
        }

        $url = $this->getEntityManager()->getRepository('Room13PageBundle:Url')->findOneByUrl($path);

        if ($url)
        {
            return $url->getPage();
        }

        return null;

    }
}