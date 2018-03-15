<?php

namespace AppBundle\Controller;

use AppBundle\Form\Type\ClubType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminClubController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/kontrollpanel/info", name="cp_info")
     * @Method({"GET", "POST"})
     */
    public function showAction(Request $request)
    {
        $club = $this->get('club_manager')->getCurrentClub();
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($club);
            $manager->flush();

            return $this->redirectToRoute('cp_info');
        }

        return $this->render('control_panel/club_info.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
