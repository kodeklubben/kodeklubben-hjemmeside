<?php

namespace CourseBundle\Controller;

use CourseBundle\Entity\Course;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class CourseController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/kurs", name="courses")
     * @Method("GET")
     */
    public function showAction()
    {
        $club = $this->get('club_manager')->getCurrentClub();
        $courses = $this->getDoctrine()->getRepository('CourseBundle:CourseType')->findAllByClub($club);

        return $this->render('@Course/show.html.twig', array(
            'courses' => $courses, ));
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
     * @Method("GET")
     */
    public function showCourseInfoAction(Course $course)
    {
        return $this->render('@Course/course_info.html.twig', array('course' => $course));
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
     * @Method("GET")
     */
    public function getCourseClassesAction($week)
    {
        $currentSemester = $this->getDoctrine()->getRepository('AppBundle:Semester')->findCurrentSemester();
        $club = $this->get('club_manager')->getCurrentClub();
        $courseClasses = $this->getDoctrine()->getRepository('CourseBundle:CourseClass')->findByWeek($week, $currentSemester, $club);

        return new JsonResponse($courseClasses);
    }
}
