<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Artwork
 *
 * @ORM\Table(name="artwork")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArtworkRepository")
 */
class Artwork
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
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=5000)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="string", length=255)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="mainColor", type="string", length=255, nullable=true)
     */
    private $mainColor;


    /**
     * @var string
     *
     * @ORM\Column(name="fontColor", type="string", length=255, nullable=true)
     */
    private $fontColor;

      /**
       * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Artist")
       * @ORM\JoinColumn(nullable=false)
       */
    private $artist;

    /**
     * @ORM\Column(type="string")
     *
     * @Assert\NotBlank(message="Please, upload the product brochure as a PDF file.")
     * @Assert\File(mimeTypes={ "image/jpeg" })
     */
    private $image;




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
     * Set title
     *
     * @param string $title
     *
     * @return Artwork
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Artwork
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set mainColor
     *
     * @param string $mainColor
     *
     * @return Artwork
     */
    public function setMainColor($mainColor)
    {
        $this->mainColor = $mainColor;

        return $this;
    }

    /**
     * Get mainColor
     *
     * @return string
     */
    public function getMainColor()
    {
        return $this->mainColor;
    }


    /**
     * Set fontColor
     *
     * @param string $fontColor
     *
     * @return Artwork
     */
    public function setFontColor($fontColor)
    {
        $this->fontColor = $fontColor;

        return $this;
    }

    /**
     * Get fontColor
     *
     * @return string
     */
    public function getFontColor()
    {
        return $this->fontColor;
    }

    /**
     * Set artist
     *
     * @param integer $artist
     *
     * @return Artwork
     */
    public function setArtist($artist)
    {
        $this->artist = $artist;

        return $this;
    }

    /**
     * Get artist
     *
     * @return int
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Artwork
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set date
     *
     * @param string $date
     *
     * @return Artwork
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return string
     */
    public function getDate()
    {
        return $this->date;
    }    
}

