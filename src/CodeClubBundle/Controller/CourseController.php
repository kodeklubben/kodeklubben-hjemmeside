<?php

namespace CodeClubBundle\Controller;

use CodeClubBundle\Entity\Course;
use CodeClubBundle\Entity\CourseClass;
use CodeClubBundle\Entity\CourseType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class CourseController extends Controller
{

    public function showAction()
    {
        $courses = $this->getDoctrine()->getRepository('CodeClubBundle:CourseType')->findAll();
        return $this->render('@CodeClub/course/show.html.twig', array(
            'courses' => $courses));
    }
    
    public function showCourseInfoAction(Course $course)
    {
        return $this->render('@CodeClub/course/course_info.html.twig', array('course' => $course));
    }

    public function getCourseClassesAction($week)
    {
        $currentSemester = $this->getDoctrine()->getRepository('CodeClubBundle:Semester')->findCurrentSemester();
        $courseClasses = $this->getDoctrine()->getRepository('CodeClubBundle:CourseClass')->findByWeek($week, $currentSemester);
        return new JsonResponse($courseClasses);
    }
    
}

