<?php

namespace Room13\PageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Room13\PageBundle\Entity\PageFeature
 *
 * @ORM\Table(name="room13_page_feature")
 * @ORM\Entity
 */
class PageFeature
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}