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
    public function artistDetailAction(Request $request, $id)
    {
        $artist = $this->getDoctrine()
            ->getRepository(Artist::class)
            ->find($id);

        if (!$artist) {
            throw $this->createNotFoundException(
                'No artist found for id '.$id
            );
        }

        $works = $this->getDoctrine()
        ->getRepository(Artwork::class)
        ->findBy(array('artist' => $artist ));

        return $this->render('AppBundle:Artist:artist_detail.html.twig', array(
            'artist' => $artist,
            'works' => $works
        ));
    }

    /**
     * @Route("/artist/new", name="new artist")
     */
    public function newArtistAction(Request $request)
    {
        $artist = new Artist();
        $form = $this->createForm(ArtistType::class, $artist);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($artist);
            $em->flush();

            return $this->render('AppBundle:Artist:artist_detail.html.twig', array(
                'artist' => $artist,
            ));        
        }

        return $this->render('AppBundle:Artist:artist_new.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * @Route("/artist/edit/{id}", name="edit_artist")
     */
    public function EditArtistAction(Request $request, $id)
    {
        $artist = $this->getDoctrine()
            ->getRepository(Artist::class)
            ->find($id);
        $form = $this->createForm(ArtistType::class, $artist);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($artist);
            $em->flush();

            return $this->render('AppBundle:Artist:artist_detail.html.twig', array(
                'artist' => $artist,
            ));        
        }

        return $this->render('AppBundle:Artist:artist_new.html.twig', array(
            'form' => $form->createView(),
            'artist' => $artist
        ));
    }


    /**
     * @Route("/artist/delete/{id}", name="delete_artist")
     */
    public function deleteArtistAction(Request $request, $id)
    {
        $artist = $this->getDoctrine()
            ->getRepository(Artist::class)
            ->find($id);

        
        $em = $this->getDoctrine()->getManager();   
        $em->remove($artist);
        $em->flush();

        $response = $this->forward('AppBundle:Artist:listArtist', array());
        return $response;

    }
}