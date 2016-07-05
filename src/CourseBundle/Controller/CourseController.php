<?php

namespace CourseBundle\Controller;

use CourseBundle\Entity\Course;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class CourseController extends Controller
{

    public function showAction()
    {
        $courses = $this->getDoctrine()->getRepository('CourseBundle:CourseType')->findAll();
        return $this->render('@Course/show.html.twig', array(
            'courses' => $courses));
    }
    
    public function showCourseInfoAction(Course $course)
    {
        return $this->render('@Course/course_info.html.twig', array('course' => $course));
    }

    public function getCourseClassesAction($week)
    {
        $currentSemester = $this->getDoctrine()->getRepository('CodeClubBundle:Semester')->findCurrentSemester();
        $courseClasses = $this->getDoctrine()->getRepository('CourseBundle:CourseClass')->findByWeek($week, $currentSemester);
        return new JsonResponse($courseClasses);
    }
    
}

