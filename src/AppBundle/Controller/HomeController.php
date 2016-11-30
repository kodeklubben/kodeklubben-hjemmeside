<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/", name="home")
     * @Method("GET")
     */
    public function showAction()
    {
        return $this->render('@App/home/show.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showMessagesAction()
    {
        $club = $this->get('club_manager')->getCurrentClub();
        $messages = $this->getDoctrine()->getRepository('AppBundle:Message')->findLatestMessages($club);

        return $this->render('@App/home/messages.html.twig', array('messages' => $messages));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showCourseTypesAction()
    {
        $currentSemester = $this->getDoctrine()->getRepository('AppBundle:Semester')->findCurrentSemester();
        $club = $this->get('club_manager')->getCurrentClub();
        $courseTypes = $this->getDoctrine()->getRepository('CourseBundle:CourseType')->findNotHiddenBySemester($currentSemester, $club);

        return $this->render('@App/home/course.html.twig', array('courseTypes' => $courseTypes));
    }

    /**
     * @param null $week
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showTimeTableAction($week = null)
    {
        if (is_null($week)) {
            $week = (new \DateTime())->format('W');
        }
        $currentSemester = $this->getDoctrine()->getRepository('AppBundle:Semester')->findCurrentSemester();
        $club = $this->get('club_manager')->getCurrentClub();
        $courseClasses = $this->getDoctrine()->getRepository('CourseBundle:CourseClass')->findByWeek($week, $currentSemester, $club);

        return $this->render('@App/home/time_table.html.twig', array(
            'courseClasses' => $courseClasses,
            'week' => $week,
            'currentSemester' => $currentSemester,
        ));
    }
}
