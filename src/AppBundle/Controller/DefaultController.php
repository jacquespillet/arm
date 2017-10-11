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

class DefaultController extends Controller
{

     /**
     * @Route("/", name="home")
     */
    public function indexAction(Request $request)
    {
        return $this->render('AppBundle::index.html.twig', array(
        ));
    }


}
