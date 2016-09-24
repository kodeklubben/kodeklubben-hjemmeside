<?php

namespace ImageBundle\Controller;

use ImageBundle\Entity\Image;
use ImageBundle\Form\Type\ImageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ImageController extends Controller
{
    /**
     * @param Request $request
     * @param $name
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * 
     * @Route("kontrollpanel/bilde/last_opp/{name}", name="image_upload")
     */
    public function uploadImageAction(Request $request, $name)
    {
        $club = $this->get('app.club_finder')->getCurrentClub();
        $image = $this->getDoctrine()->getRepository('ImageBundle:Image')->findByClubAndName($club, $name);
        if (is_null($image)) {
            throw $this->createNotFoundException('Bildenavn finnes ikke');
        }

        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.image_uploader')->uploadImage($image);

            return $this->redirectToRoute('home');
        }

        return $this->render('ImageBundle::upload_image.html.twig', array(
            'form' => $form->createView(),
            'image' => $image,
        ));
    }
}
