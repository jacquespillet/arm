<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Artist;
use AppBundle\Entity\InterestPoints;
use AppBundle\Form\ArtistType;
use AppBundle\Form\InterestPointsType;
use AppBundle\Entity\Artwork;
use AppBundle\Form\ArtworkType;
use \Datetime;
use \DateTimeZone;

class InterestPointsController extends Controller
{ 

	/**
     * @Route("/interestPoint/new/{artwork_id}", name="new interestPoint")
     */
    public function newInterestPointAction(Request $request, $artwork_id)
    {
        $artwork = $this->getDoctrine()
            ->getRepository(Artwork::class)
            ->find($artwork_id);

        if (!$artwork) {
            throw $this->createNotFoundException(
                'No artwork found for artwork_id '.$artwork_id
            );
        }

        $poi = new InterestPoints();
        $poi->setArtwork($artwork);
        $form = $this->createForm(InterestPointsType::class, $poi);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($poi);
            $em->flush();


            $response = $this->forward('AppBundle:Artwork:artworkDetail', array(
                'id'  => $artwork_id,
            )); 
            return $response;
        }

        return $this->render('AppBundle:InterestPoint:interestPoint.html.twig', array(
            'form' => $form->createView(),
            'artwork' => $artwork
        ));
    }

}