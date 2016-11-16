<?php

namespace CourseBundle\Controller;

use CourseBundle\Entity\Course;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CourseController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/kurs", name="courses")
     */
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

    /**
     * @param Course $course
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/kurs/{id}",
     *     options={"expose"=true},
     *     requirements={"id"="\d+"},
     *     name="course_info"
     * )
     */
    public function showCourseInfoAction(Course $course)
    {
        $response = $this->render('@Course/course_info.html.twig', array('course' => $course));

        // Set cache expiration time to 5 minutes
//        $response->setSharedMaxAge(300);

//        $response->headers->addCacheControlDirective('must-revalidate', true);

        return $response;
    }

    /**
     * @param $week
     *
     * @return JsonResponse
     *
     * @Route("/api/kurs/uke/{week}",
     *     name="api_get_course_classes_by_week",
     *     requirements={"id" = "\d+"}
     * )
     */
    public function getCourseClassesAction($week)
    {
        $currentSemester = $this->getDoctrine()->getRepository('AppBundle:Semester')->findCurrentSemester();
        $courseClasses = $this->getDoctrine()->getRepository('CourseBundle:CourseClass')->findByWeek($week, $currentSemester);

        return new JsonResponse($courseClasses);
    }
}
