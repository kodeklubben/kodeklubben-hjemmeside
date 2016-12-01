<?php

namespace ClubManagerBundle\Controller;

use CodeClubBundle\Entity\Club;
use CodeClubBundle\Form\Type\ClubType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="clubs")
     * @Method({"GET", "POST"})
     */
    public function homeAction(Request $request)
    {
        $club = new Club();
        $form = $this->createForm(ClubType::class, $club);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($club);
            $em->flush();

            $this->get('club_registration')->createAdminAndSendRegistrationEmail($club);

            return $this->render('@ClubManager/registration_complete.html.twig', array(
               'club' => $club,
            ));
        }

        $clubs = $this->getDoctrine()->getRepository('CodeClubBundle:Club')->findAllSorted();

        return $this->render('@ClubManager/home.html.twig', array(
            'clubs' => $clubs,
            'form' => $form->createView(),
        ));
    }
}
