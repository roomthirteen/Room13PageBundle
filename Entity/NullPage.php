<?php

namespace Room13\PageBundle\Entity;

class NullPage extends Page
{

    public function __construct($path)
    {
        $this->path = $path;

    }

    public function getPath()
    {
        return $this->path;
    }


}
