<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reaction
 *
 * @ORM\Table(name="reaction")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ReactionRepository")
 */
class Reaction
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Artwork")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idArtwork;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idUser;


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
     * Set idArtwork
     *
     * @param integer $idArtwork
     *
     * @return Reaction
     */
    public function setIdArtwork($idArtwork)
    {
        $this->idArtwork = $idArtwork;

        return $this;
    }

    /**
     * Get idArtwork
     *
     * @return int
     */
    public function getIdArtwork()
    {
        return $this->idArtwork;
    }

    /**
     * Set idUser
     *
     * @param integer $idUser
     *
     * @return Reaction
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return int
     */
    public function getIdUser()
    {
        return $this->idUser;
    }
}

