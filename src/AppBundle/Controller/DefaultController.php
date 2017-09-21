<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Artist;
use AppBundle\Form\ArtistType;
use AppBundle\Entity\Artwork;
use AppBundle\Form\ArtworkType;

class DefaultController extends Controller
{
    /**
     * @Route("/artist/new", name="new artist")
     */
    public function indexAction(Request $request)
    {
        $artist = new Artist();
        $form = $this->createForm(ArtistType::class, $artist);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($artist);
            $em->flush();

            return $this->render('AppBundle::artist.html.twig', array(
                'form' => $form->createView(),
            ));        
        }

        return $this->render('AppBundle::artist.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/artwork/new", name="new artwork")
     */
    public function newAction(Request $request)
    {
        //Server Keys
        $access_key     = "2b9f683e6952c2db65984c2d6b3b7de03e24800a";
        $secret_key     = "abe7d49c676f2a4906160842d276ae3dbfa16fc2";
        
        //private $targetId         = "eda03583982f41cdbe9ca7f50734b9a1";
        $url            = "https://vws.vuforia.com";
        $requestPath    = "/targets";
        $request;       // the HTTP_Request2 object
        $jsonRequestObject;
        $targetName     = "test";
        $imageLocation  = "C:\wamp64\www\ARMbo\web\uploads\brochures\8e81d7751ce601bf6266b3068a354087.jpeg";

$StringToSign = 
"GET\n".
"d41d8cd98f00b204e9800998ecf8427e\n".
"application/json\n".
"Sun, 22 Apr 2012 08:49:37 GMT\n".
"https://vws.vuforia.com/targets/";



        // $file = file_get_contents( $imageLocation );
        // if( $file ){
        //     $file = base64_encode( $file );
        // }
        // $jsonRequestObject = json_encode( array( 'width'=>320.0 , 'name'=>$targetName , 'image'=>$file, 'application_metadata'=>base64_encode("Vuforia test metadata") , 'active_flag'=>1 ) );
        // // var_dump($jsonRequestObject);

        // $ch = curl_init($url . $requestPath);

        // $content = md5($jsonRequestObject, false);
        // var_dump($content);
        // $date = new DateTime("now", new DateTimeZone("GMT"));


/*
        $StringToSign =
          "POST\n" .
          $content . "\n" .
          "application/json\n" .
          $date->format("D, d M Y H:i:s") . " GMT" . "\n" .
          $requestPath;

          $signature = base64_encode(hmac_sha1($secret_key, $StringToSign));


        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => array(
                "Authorization: VWS " . $access_key . ":" . $signature,
                'Content-Type: application/json'
            ),
            CURLOPT_POSTFIELDS => json_encode($postData)
        ));*/


        // $request = new HTTP_Request2();
        // $request->setMethod( HTTP_Request2::METHOD_POST );
        // $request->setBody( $this->jsonRequestObject );

        // $request->setConfig(array(
        //         'ssl_verify_peer' => false
        // ));
        // $request->setURL( $this->url . $this->requestPath );

        // $date = new DateTime("now", new DateTimeZone("GMT"));
        // // Define the Date field using the proper GMT format
        // $request->setHeader('Date', $date->format("D, d M Y H:i:s") . " GMT" );
        
        $request->setHeader("Content-Type", "application/json" );
        // Generate the Auth field value by concatenating the public server access key w/ the private query signature for this request
        $request->setHeader();

        // var_dump($request);



        // $execPostNewTarget();


        // $artwork = new Artwork();
        // $form = $this->createForm(ArtworkType::class, $artwork);
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {
        //     $file = $artwork->getImage();
        //     $fileName = md5(uniqid()).'.'.$file->guessExtension();
        //     $file->move(
        //         $this->getParameter('artwork_directory'),
        //         $fileName
        //     );

        //     $artwork->setImage($fileName);

        //     $em = $this->getDoctrine()->getManager();
        //     $em->persist($artwork);
        //     $em->flush();

        //     return $this->render('AppBundle::artwork.html.twig', array(
        //         'form' => $form->createView(),
        //     ));        
        // }

        // return $this->render('AppBundle::artwork.html.twig', array(
        //     'form' => $form->createView(),
        // ));
    }

    function hmac_sha1($key, $data)
    {
        // Adjust key to exactly 64 bytes
        if (strlen($key) > 64) {
            $key = str_pad(sha1($key, true), 64, chr(0));
        }
        if (strlen($key) < 64) {
            $key = str_pad($key, 64, chr(0));
        }

        // Outter and Inner pad
        $opad = str_repeat(chr(0x5C), 64);
        $ipad = str_repeat(chr(0x36), 64);

        // Xor key with opad & ipad
        for ($i = 0; $i < strlen($key); $i++) {
            $opad[$i] = $opad[$i] ^ $key[$i];
            $ipad[$i] = $ipad[$i] ^ $key[$i];
        }

        return sha1($opad.sha1($ipad.$data, true));
    }
}
