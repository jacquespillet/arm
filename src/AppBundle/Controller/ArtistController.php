<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Artist;
use AppBundle\Form\ArtistType;
use AppBundle\Entity\Artwork;
use AppBundle\Form\ArtworkType;
use \Datetime;
use \DateTimeZone;

class ArtistController extends Controller
{
    /**
     * @Route("/artist/list", name="list artist")
     */
    public function listArtistAction(Request $request)
    {
    	$artists = $this->getDoctrine()->getRepository(Artist::class)->findAll();
        return $this->render('AppBundle:Artist:artist_list.html.twig', array(
            'artists' => $artists,
        ));
    }

    /**
     * @Route("/artist/detail/{id}", name="artist detail")
     */
    public function artworkDetailAction(Request $request, $id)
    {
        $artist = $this->getDoctrine()
            ->getRepository(Artist::class)
            ->find($id);

        if (!$artist) {
            throw $this->createNotFoundException(
                'No artist found for id '.$id
            );
        }

        return $this->render('AppBundle:Artist:artist_detail.html.twig', array(
            'artist' => $artist,
        ));
    }

}