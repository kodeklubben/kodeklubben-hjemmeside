<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class ControlPanelController.
 *
 * @Route("/kontrollpanel")
 */
class ControlPanelController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @Route("/", name="control_panel")
     * @Method("GET")
     */
    public function showAction()
    {
        $club = $this->get('club_manager')->getCurrentClub();
        $doctrine = $this->getDoctrine();
        $currentSemester = $this->getDoctrine()->getRepository('AppBundle:Semester')->findCurrentSemester();
        $userCount = count($doctrine->getRepository('AppBundle:User')->findByClub($club));
        $newUsersCurrentSemesterCount = count($doctrine->getRepository('AppBundle:User')->findNewUsersBySemester($currentSemester, $club));
        $participantCount = count($doctrine->getRepository('AppBundle:Participant')->findByClub($club));
        $participantCountCurrentSemester = count($doctrine->getRepository('AppBundle:Participant')->findBySemester($currentSemester, $club));
        $tutorCount = count($doctrine->getRepository('AppBundle:Tutor')->findByClub($club));
        $tutorCountCurrentSemester = count($doctrine->getRepository('AppBundle:Tutor')->findBySemester($currentSemester, $club));
        $courseCount = count($doctrine->getRepository('AppBundle:Course')->findByClub($club));
        $courseCountCurrentSemester = count($doctrine->getRepository('AppBundle:Course')->findBySemester($currentSemester, $club));

        return $this->render('control_panel/show.html.twig', array(
            'userCount' => $userCount,
            'newUserCount' => $newUsersCurrentSemesterCount,
            'participantCount' => $participantCount,
            'newParticipantCount' => $participantCountCurrentSemester,
            'tutorCount' => $tutorCount,
            'newTutorCount' => $tutorCountCurrentSemester,
            'courseCount' => $courseCount,
            'newCourseCount' => $courseCountCurrentSemester,
            'currentSemester' => $currentSemester,
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @Route("/epost", name="cp_email")
     * @Method("GET")
     */
    public function showEmailAction()
    {
        $club = $this->get('club_manager')->getCurrentClub();
        $currentSemester = $this->getDoctrine()->getRepository('AppBundle:Semester')->findCurrentSemester();
        $courses = $this->getDoctrine()->getRepository('AppBundle:Course')->findBySemester($currentSemester, $club);

        return $this->render('control_panel/show_email.html.twig', array(
            'semester' => $currentSemester,
            'courses' => $courses,
        ));
    }
}
