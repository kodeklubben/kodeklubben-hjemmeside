<?php

namespace CodeClubBundle\Controller;

use CodeClubBundle\Form\ClubType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminClubController extends Controller
{
    public function showAction(Request $request)
    {
        $club = $this->get('app.club_finder')->getCurrentClub();
        $form = $this->createForm(new ClubType(), $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($club);
            $manager->flush();
        }

        return $this->render('@CodeClub/control_panel/club_info.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
