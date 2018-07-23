<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Favorite
 *
 * @ORM\Table(name="favorite")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FavoriteRepository")
 */
class Favorite
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="charactherId", type="integer")     *
     */
    private $charactherId;



    /**
     * @var boolean
     * @ORM\Column(name="isSet", type="boolean")
     */
    private $isSet;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getCharactherId()
    {
        return $this->charactherId;
    }

    /**
     * @param int $charactherId
     */
    public function setCharactherId($charactherId)
    {
        $this->charactherId = $charactherId;
    }

    /**
     * @return bool
     */
    public function getisSet()
    {
        return $this->isSet;
    }

    /**
     * @param bool $isSet
     */
    public function setIsSet($isSet)
    {
        $this->isSet = $isSet;
    }


}

