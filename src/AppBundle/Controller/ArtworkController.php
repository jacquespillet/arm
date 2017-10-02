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

class ArtworkController extends Controller
{
    /**
     * @Route("/artwork/detail/{id}", name="artwork detail")
     */
    public function artworkDetailAction(Request $request, $id)
    {
    	$artwork = $this->getDoctrine()
	        ->getRepository(Artwork::class)
	        ->find($id);

	    if (!$artwork) {
	        throw $this->createNotFoundException(
	            'No artwork found for id '.$id
	        );
	    }

        return $this->render('AppBundle:Artwork:artworkDetail.html.twig', array(
        	'title'   => $artwork->getTitle(),
            'artwork' => $artwork,
        ));
    }
}