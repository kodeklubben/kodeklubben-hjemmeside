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
        $response = $this->render('@Course/show.html.twig', array(
            'courses' => $courses, ));

        // Set cache expiration time to 5 minutes
        $response->setSharedMaxAge(300);

        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }

    public function showCourseInfoAction(Course $course)
    {
        $response = $this->render('@Course/course_info.html.twig', array('course' => $course));

        // Set cache expiration time to 5 minutes
//        $response->setSharedMaxAge(300);

//        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }

    public function getCourseClassesAction($week)
    {
        $currentSemester = $this->getDoctrine()->getRepository('CodeClubBundle:Semester')->findCurrentSemester();
        $courseClasses = $this->getDoctrine()->getRepository('CourseBundle:CourseClass')->findByWeek($week, $currentSemester);

        return new JsonResponse($courseClasses);
    }
}
