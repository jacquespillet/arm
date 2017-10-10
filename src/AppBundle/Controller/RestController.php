<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Artist;
use AppBundle\Entity\Reaction;
use AppBundle\Entity\Artwork;
use AppBundle\Entity\User;
use AppBundle\Entity\InterestPoints;
use AppBundle\Entity\Comment;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


class RestController extends Controller
{
    /**
     * @Route("/api/artwork/{name}", name="get artists")
     * @Method("GET")     
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

		$comments = $this->getDoctrine()
		->getRepository(Comment::class)
		->findBy(array('idArtwork' => $artwork));
		$response["comments"] = json_decode($this->container->get('jms_serializer')->serialize($comments, 'json'), true);

		$reactions = $this->getDoctrine()
		->getRepository(Reaction::class)
		->findBy(array('idArtwork' => $artwork));
		$response["reactions"] = json_decode($this->container->get('jms_serializer')->serialize($reactions, 'json'), true);

		return new JsonResponse($response);
    }

    /**
     * @Route("/api/addReact", name="add reaction")
     * @Method("POST")     
     */
    public function addReaction(Request $request){

    	$em = $this->getDoctrine()->getManager();
		
		$user =  $this->getDoctrine()
		->getRepository(User::class)
		->findOneByUsername($request->request->get('user'));
		
		$artwork =  $this->getDoctrine()
		->getRepository(Artwork::class)
		->find($request->request->get('artwork'));

		$reaction = $this->getDoctrine()
		->getRepository(Reaction::class)
		->findOneBy(
			array('idArtwork' => $artwork->getId(), 'idUser' => $user->getId())
		);

		if($reaction!=null){
			$em->remove($reaction);
		    $response = array("status" => "removed reaction" . $reaction->getId() . " on artwork " . $artwork->getTitle() . " by user " . $user->getUsername() );	

		} else {
		    $reaction = new Reaction();
		    $reaction->setIdUser($user);
		    $reaction->setIdArtwork($artwork);

		    $em->persist($reaction);

		    $response = array( "status" => "added reaction" . $reaction->getId() . " on artwork " . $artwork->getTitle() . " by user " . $user->getUsername() );	
		}

	    $em->flush();

	    return new JsonResponse($response);
    }

    /**
     * @Route("/api/addComment", name="add comment")
     * @Method("POST")     
     */
    public function addComment(Request $request){

    	$em = $this->getDoctrine()->getManager();
		
		$user =  $this->getDoctrine()
		->getRepository(User::class)
		->findOneByUsername($request->request->get('user'));
		
		$artwork =  $this->getDoctrine()
		->getRepository(Artwork::class)
		->find($request->request->get('artwork'));

	    $comment = new Comment();
	    $comment->setIdUser($user);
	    $comment->setIdArtwork($artwork);
	    $comment->setComment($request->request->get('comment'));

	    $em->persist($comment);

	    $response = array( "status" => "added comment" . $comment->getId() . " on artwork " . $artwork->getTitle() . " by user " . $user->getUsername() );	

	    $em->flush();

	    return new JsonResponse($response);
    }

    /**
     * @Route("/api/users", name="get users")
     * @Method("GET")     
     */
    public function getUsers(Request $request){
    	$response = array();
		$users =  $this->getDoctrine()
		->getRepository(User::class)
		->findAll();
		$response = json_decode($this->container->get('jms_serializer')->serialize($users, 'json'), true);
			
		for($i=0;$i<count($response);$i++){
			unset($response[$i]["roles"]);
		}


	    return new JsonResponse($response);
    }

}