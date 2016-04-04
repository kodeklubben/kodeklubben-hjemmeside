<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Course;
use AppBundle\Entity\CourseClass;
use AppBundle\Entity\CourseType;
use AppBundle\Form\CourseClassType;
use AppBundle\Form\CourseFormType;
use AppBundle\Form\CourseTypeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CourseController extends Controller
{

    public function showAction()
    {
        $courses = $this->getDoctrine()->getRepository('AppBundle:CourseType')->findAll();
        return $this->render(':courses:index.html.twig', array(
            'courses' => $courses));
    }


    public function showCourseTypeAction()
    {
        $courses = $this->getDoctrine()->getRepository('AppBundle:CourseType')->findAll();
        return $this->render('control_panel/courses/showType.html.twig', array(
            'courses' => $courses,
        ));
    }

    public function editCourseTypeAction(Request $request, CourseType $courseType = null)
    {
        if (is_null($courseType)) $courseType = new CourseType();
        $form = $this->createForm(new CourseTypeType(), $courseType);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($courseType);
            $manager->flush();
            return $this->redirectToRoute('cp_course_type');
        }
        return $this->render('control_panel/courses/showEditType.html.twig', array(
            'courseType' => $courseType,
            'form' => $form->createView()
        ));
    }

    public function deleteCourseTypeAction(Request $request, CourseType $courseType)
    {
        $courseType->delete();
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($courseType);
        $manager->flush();
        return $this->redirectToRoute('cp_course_type');
    }

    public function showCourseAction()
    {
        $courses = $this->getDoctrine()->getRepository('AppBundle:Course')->findAll();
        return $this->render('control_panel/courses/show.html.twig', array(
            'courses' => $courses
        ));
    }

    public function editCourseAction(Request $request, Course $course = null)
    {
        if (is_null($course)) $course = new Course();
        $form = $this->createForm(new CourseFormType(), $course);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($course);
            $manager->flush();
            return $this->redirectToRoute('cp_course');
        }
        return $this->render('control_panel/courses/createCourse.html.twig', array(
            'course' => $course,
            'form' => $form->createView()
        ));
    }

    public function deleteCourseAction(Request $request, Course $course)
    {
        $course->delete();
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($course);
        $manager->flush();
        return $this->redirectToRoute('cp_course');
    }

    public function editTimeTableAction(Request $request, Course $course)
    {
        $courseClass = new CourseClass();
        $latestCourseClass = $this->getDoctrine()->getRepository('AppBundle:CourseClass')->findOneBy(array('course' => $course), array('time' => 'DESC'), 1);

        //Set default time to one week after last class
        if (!is_null($latestCourseClass)) {
            $courseClass->setTime(clone $latestCourseClass->getTime());
            $courseClass->getTime()->modify('+7day');
            $courseClass->setPlace($latestCourseClass->getPlace());
        } else {
            $courseClass->setTime(new \DateTime());
        }

        $form = $this->createForm(new CourseClassType(), $courseClass);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $courseClass->setCourse($course);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($courseClass);
            $manager->flush();

            return $this->redirectToRoute('cp_course_time_table', array('id' => $course->getId()));
        }
        return $this->render('control_panel/courses/timeTable.html.twig', array(
            'course' => $course,
            'form' => $form->createView()
        ));
    }

    public function deleteCourseClassAction($id){
        $manager = $this->getDoctrine()->getManager();
        $courseClass = $manager->getRepository('AppBundle:CourseClass')->find($id);
        if(!is_null($courseClass)){
            $manager->remove($courseClass);
            $manager->flush();
        }
        return $this->redirectToRoute('cp_course_time_table', array(
            'id' => $courseClass->getCourse()->getId()
        ));
    }

    public function getCourseClassesAction($week)
    {
        $courseClasses = $this->getDoctrine()->getRepository('AppBundle:CourseClass')->findByWeek($week);
        return new JsonResponse($courseClasses);
    }

}
