<?php

namespace CodeClubBundle\Controller;

use CodeClubBundle\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function showAction()
    {
        return $this->render('@CodeClub/home/show.html.twig');
    }

    public function showMessagesAction()
    {
        $messages = $this->getDoctrine()->getRepository('CodeClubBundle:Message')->findLatestMessages();

        return $this->render('@CodeClub/home/messages.html.twig', array('messages' => $messages));

    }

    public function showCourseTypesAction(){
        $currentSemester = $this->getDoctrine()->getRepository('CodeClubBundle:Semester')->findCurrentSemester();
        $courseTypes = $this->getDoctrine()->getRepository('CodeClubBundle:CourseType')->findNotHiddenBySemester($currentSemester);

        return $this->render('@CodeClub/home/course.html.twig', array('courseTypes' => $courseTypes));
    }

    public function showTimeTableAction($week = null){
        if(is_null($week))$week = (new \DateTime())->format('W');
        $currentSemester = $this->getDoctrine()->getRepository('CodeClubBundle:Semester')->findCurrentSemester();
        $courseClasses = $this->getDoctrine()->getRepository('CodeClubBundle:CourseClass')->findByWeek($week, $currentSemester);
        return $this->render('@CodeClub/home/time_table.html.twig',array(
            'courseClasses' => $courseClasses,
            'week' => $week,
            'currentSemester' => $currentSemester,
        ));
    }
}
