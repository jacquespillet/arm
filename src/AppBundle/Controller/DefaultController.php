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
    const JSON_CONTENT_TYPE = 'application/json';
    const ACCESS_KEY = '2b9f683e6952c2db65984c2d6b3b7de03e24800a';
    const SECRET_KEY = 'abe7d49c676f2a4906160842d276ae3dbfa16fc2';
    const BASE_URL = 'https://vws.vuforia.com';
    const TARGETS_PATH = '/targets';

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

            return $this->render('AppBundle::artist.html.twig', array(
                'form' => $form->createView(),
            ));        
        }

        return $this->render('AppBundle::artist.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/artist/list", name="list artist")
     */
    public function listArtistAction(Request $request)
    {

        return $this->render('AppBundle::artist_list.html.twig', array(
            'form' => 'form',
        ));
    }

    /**
     * @Route("/artwork/new", name="new artwork")
     */
    public function newArtworkAction(Request $request)
    {
        $artwork = new Artwork();
        $form = $this->createForm(ArtworkType::class, $artwork);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $artwork->getImage();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('artwork_directory'),
                $fileName
            );

            $artwork->setImage($fileName);

            $em = $this->getDoctrine()->getManager();
            $em->persist($artwork);
            $em->flush();


            $this->addTarget( $this->getParameter('artwork_directory') . "/", $fileName);

            // return $this->render('AppBundle:Artwork:artworkDetail.html.twig', array(
            //     'artwork' => $artwork
            // ));
        }

        return $this->render('AppBundle::artwork.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/artwork/list", name="list artwork")
     */
    public function listArtworkAction(Request $request)
    {

        return $this->render('AppBundle::artwork_list.html.twig', array(
            'form' => 'form',
        ));
    }

    /**
     * Add a target to the Vuforia database accessed by the given keys.
     * @param uploadPath - Path to the folder of the image (E.G. '../content/images/')
     * @param imageName - Name of the image with fileExtension (E.G. 'myimage.jpg)
     * @return [String] - Vuforia target ID
     */
    function addTarget($imagePath,$imageName) {
        $imagePath = $imagePath;
        $imageName = $imageName;
        $ch = curl_init(self::BASE_URL . self::TARGETS_PATH);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $image = file_get_contents($imagePath.$imageName);
        $image_base64 = base64_encode($image);
        // Use date to create unique filenames on server
        $date = new DateTime();
        $dateTime = $date->getTimestamp();
        $file = pathinfo($imageName);
        $filename       = $file['filename'];
        $fileextension = $file['extension'];
        $post_data = array(
            'name' => $filename . $fileextension,
            'width' => 32.0,
            'image' => $image_base64,
            'application_metadata' => $this->createMetadata($imagePath, $imageName),
            'active_flag' => 1
        );
        $body = json_encode($post_data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getHeaders('POST', self::TARGETS_PATH, self::JSON_CONTENT_TYPE, $body));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        if ($info['http_code'] !== 201) {
            print 'Failed to add target: ' . $response;
            return 'none';
        } else {
            $vuforiaTargetID = json_decode($response)->target_id;
            print 'Successfully added target: ' . $vuforiaTargetID . "\n";
            return $vuforiaTargetID;
        }
    }

    /**
    * Create a request header.
    * @return [Array] Header for request.
    */
    private function getHeaders($method, $path = self::TARGETS_PATH, $content_type = '', $body = '') {
        $headers = array();
        $date = new DateTime("now", new DateTimeZone("GMT"));
        $dateString = $date->format("D, d M Y H:i:s") . " GMT";
        $md5 = md5($body, false);
        $string_to_sign = $method . "\n" . $md5 . "\n" . $content_type . "\n" . $dateString . "\n" . $path;
        $signature = $this->hexToBase64(hash_hmac("sha1", $string_to_sign, self::SECRET_KEY));
        $headers[] = 'Authorization: VWS ' . self::ACCESS_KEY . ':' . $signature;
        $headers[] = 'Content-Type: ' . $content_type;
        $headers[] = 'Date: ' . $dateString;
        return $headers;
    }
    private function hexToBase64($hex){
        $return = "";
        foreach(str_split($hex, 2) as $pair){
            $return .= chr(hexdec($pair));
        }
        return base64_encode($return);
    }
    /**
    * Create a metadata for request. You can write any information into the metadata array you want to store.
    * @return [Array] Metadata for request.
    */
    private function createMetadata($imagePath, $imageName) {
        $metadata = array(
            'id' => 1,
            'image_url' => $imagePath.$imageName
        );
        return base64_encode(json_encode($metadata));
    }

}
