<?php

namespace ImageBundle\Controller;

use ImageBundle\Entity\Image;
use ImageBundle\Form\ImageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }

    public function uploadImageAction(Request $request, $name)
    {
        $club = $this->get('app.club_finder')->getCurrentClub();
        $image = $this->getDoctrine()->getRepository('ImageBundle:Image')->findByClubAndName($club, $name);
        if(is_null($image)) throw $this->createNotFoundException('Bildenavn finnes ikke');
        
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.image_uploader')->uploadImage($image);

            return $this->redirectToRoute('home');
        }
        return $this->render('ImageBundle::upload_image.html.twig', array(
            'form' => $form->createView(),
            'image' => $image
        ));
    }
}
