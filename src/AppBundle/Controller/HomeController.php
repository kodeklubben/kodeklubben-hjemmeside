<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class HomeController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/", name="home")
     */
    public function showAction()
    {
        $response = $this->render('@App/home/show.html.twig');

        // Set cache expiration time to 5 minutes
//        $response->setSharedMaxAge(300);

//        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }

    public function showMessagesAction()
    {
        $club = $this->get('club_manager')->getCurrentClub();
        $messages = $this->getDoctrine()->getRepository('AppBundle:Message')->findLatestMessages($club);

        return $this->render('@App/home/messages.html.twig', array('messages' => $messages));
    }

    public function showCourseTypesAction()
    {
        $currentSemester = $this->getDoctrine()->getRepository('AppBundle:Semester')->findCurrentSemester();
        $club = $this->get('club_manager')->getCurrentClub();
        $courseTypes = $this->getDoctrine()->getRepository('CourseBundle:CourseType')->findNotHiddenBySemester($currentSemester, $club);

        return $this->render('@App/home/course.html.twig', array('courseTypes' => $courseTypes));
    }

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
