<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
     * @Route("/", name="control_panel")
     * @Method("GET")
     */
    public function showAction()
    {
        $club = $this->get('club_manager')->getCurrentClub();
        $doctrine = $this->getDoctrine();
        $currentSemester = $this->getDoctrine()->getRepository('AppBundle:Semester')->findCurrentSemester();
        $userCount = count($doctrine->getRepository('UserBundle:User')->findByClub($club));
        $newUsersCurrentSemesterCount = count($doctrine->getRepository('UserBundle:User')->findNewUsersBySemester($currentSemester, $club));
        $participantCount = count($doctrine->getRepository('UserBundle:Participant')->findByClub($club));
        $participantCountCurrentSemester = count($doctrine->getRepository('UserBundle:Participant')->findBySemester($currentSemester, $club));
        $tutorCount = count($doctrine->getRepository('UserBundle:Tutor')->findByClub($club));
        $tutorCountCurrentSemester = count($doctrine->getRepository('UserBundle:Tutor')->findBySemester($currentSemester, $club));
        $courseCount = count($doctrine->getRepository('CourseBundle:Course')->findByClub($club));
        $courseCountCurrentSemester = count($doctrine->getRepository('CourseBundle:Course')->findBySemester($currentSemester, $club));

        return $this->render('@App/control_panel/show.html.twig', array(
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
     * @Route("/epost", name="cp_email")
     * @Method("GET")
     */
    public function showEmailAction()
    {
        $club = $this->get('club_manager')->getCurrentClub();
        $currentSemester = $this->getDoctrine()->getRepository('AppBundle:Semester')->findCurrentSemester();
        $courses = $this->getDoctrine()->getRepository('CourseBundle:Course')->findBySemester($currentSemester, $club);

        return $this->render('@App/control_panel/show_email.html.twig', array(
            'semester' => $currentSemester,
            'courses' => $courses,
        ));
    }
}
