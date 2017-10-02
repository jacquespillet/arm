<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Artist;
use AppBundle\Entity\Artwork;
use AppBundle\Entity\InterestPoints;
use Symfony\Component\HttpFoundation\JsonResponse;


class RestController extends Controller
{
    /**
     * @Route("/api/artwork/{name}", name="get artists")
     */
    public function getArtistsAction(Request $request, $name)
    {
    	$response = array();
		
		$artwork =  $this->getDoctrine()
		->getRepository(Artwork::class)
		->findOneByImage($name);		
		$response["artwork"] = json_decode($this->container->get('jms_serializer')->serialize($artwork, 'json'), true);


		$POIs = $this->getDoctrine()
		->getRepository(InterestPoints::class)
		->findBy(array('artwork' => $artwork));
		$response["POIs"] = json_decode($this->container->get('jms_serializer')->serialize($POIs, 'json'), true);


		$otherWorks = $this->getDoctrine()
		->getRepository(Artwork::class)
		->findBy(array('artist' => $artwork->getArtist()));
		$response["otherWorks"] = json_decode($this->container->get('jms_serializer')->serialize($otherWorks, 'json'), true);



		return new JsonResponse($response);
    }
}