<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Course;
use AppBundle\Entity\Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function showAction()
    {

        return $this->render('home/show.html.twig');
    }

    public function showMessagesAction()
    {
        $messages = $this->getDoctrine()->getRepository('AppBundle:Message')->findLatestMessages();

        return $this->render('home/messages.html.twig', array('messages' => $messages));

    }

    public function showCourseTypesAction(){
        $courseTypes = $this->getDoctrine()->getRepository('AppBundle:CourseType')->findAll();

        return $this->render('home/course.html.twig', array('courses' => $courseTypes));
    }

    public function showTimeTableAction($week = null){
        if(is_null($week))$week = (new \DateTime())->format('W');
        $currentSemester = $this->getDoctrine()->getRepository('AppBundle:Semester')->findCurrentSemester();
        $courseClasses = $this->getDoctrine()->getRepository('AppBundle:CourseClass')->findByWeek($week, $currentSemester);
        return $this->render('home/timeTable.html.twig',array(
            'courseClasses' => $courseClasses,
            'week' => $week,
            'currentSemester' => $currentSemester,
        ));
    }
}
