<?php

namespace CodeClubBundle\Controller;

use CodeClubBundle\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function showAction()
    {
        $response = $this->render('@CodeClub/home/show.html.twig');

        // Set cache expiration time to 5 minutes
//        $response->setSharedMaxAge(300);

//        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }

    public function showMessagesAction()
    {
        $messages = $this->getDoctrine()->getRepository('CodeClubBundle:Message')->findLatestMessages();

        return $this->render('@CodeClub/home/messages.html.twig', array('messages' => $messages));

    }

    public function showCourseTypesAction(){
        $currentSemester = $this->getDoctrine()->getRepository('CodeClubBundle:Semester')->findCurrentSemester();
        $courseTypes = $this->getDoctrine()->getRepository('CourseBundle:CourseType')->findNotHiddenBySemester($currentSemester);

        return $this->render('@CodeClub/home/course.html.twig', array('courseTypes' => $courseTypes));
    }

    public function showTimeTableAction($week = null){
        if(is_null($week))$week = (new \DateTime())->format('W');
        $currentSemester = $this->getDoctrine()->getRepository('CodeClubBundle:Semester')->findCurrentSemester();
        $courseClasses = $this->getDoctrine()->getRepository('CourseBundle:CourseClass')->findByWeek($week, $currentSemester);
        return $this->render('@CodeClub/home/time_table.html.twig',array(
            'courseClasses' => $courseClasses,
            'week' => $week,
            'currentSemester' => $currentSemester,
        ));
    }
}
