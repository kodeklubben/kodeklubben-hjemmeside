<?php

namespace CourseBundle\Controller;

use CourseBundle\Entity\Course;
use CourseBundle\Entity\CourseClass;
use CourseBundle\Form\CourseClassType;
use CourseBundle\Form\CourseFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminCourseController extends Controller
{
    public function showAction(Request $request)
    {
        $semesterId =$request->query->get('semester');
        $semesterRepo = $this->getDoctrine()->getRepository('CodeClubBundle:Semester');
        if(!is_null($semesterId))
        {
            $semester = $semesterRepo->find($semesterId);
        }else{
            $semester = $semesterRepo->findCurrentSemester();
        }
        $semesters = $semesterRepo->findAll();
        $courses = $this->getDoctrine()->getRepository('CourseBundle:Course')->findBySemester($semester);
        return $this->render('@Course/control_panel/show.html.twig', array(
            'courses' => $courses,
            'semester' => $semester,
            'semesters' => $semesters
        ));
    }

    public function editCourseAction(Request $request, Course $course = null)
    {
        $isCreateAction = is_null($course);
        if ($isCreateAction) $course = new Course();
        $form = $this->createForm(new CourseFormType(!$isCreateAction), $course);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($course);
            $manager->flush();
            return $this->redirectToRoute('cp_course');
        }
        return $this->render('@Course/control_panel/show_create_course.html.twig', array(
            'course' => $course,
            'form' => $form->createView()
        ));
    }

    public function editTimeTableAction(Request $request, Course $course)
    {
        $courseClass = new CourseClass();
        $latestCourseClass = $this->getDoctrine()->getRepository('CourseBundle:CourseClass')->findOneBy(array('course' => $course), array('time' => 'DESC'), 1);

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
        return $this->render('@Course/control_panel/show_time_table.html.twig', array(
            'course' => $course,
            'form' => $form->createView()
        ));
    }

    public function deleteCourseAction(Course $course)
    {
        $course->delete();
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($course);
        $manager->flush();
        return $this->redirectToRoute('cp_course');
    }

    public function showParticipantsAction(Course $course)
    {
        return $this->render('@Course/control_panel/show_course_participants.html.twig', array('course' => $course));
    }

    public function showTutorsAction(Course $course)
    {
        return $this->render('@Course/control_panel/show_course_tutors.html.twig', array('course' => $course));
    }

    public function deleteCourseClassAction($id){
        $manager = $this->getDoctrine()->getManager();
        $courseClass = $manager->getRepository('CourseBundle:CourseClass')->find($id);
        if(!is_null($courseClass)){
            $manager->remove($courseClass);
            $manager->flush();
        }
        return $this->redirectToRoute('cp_course_time_table', array(
            'id' => $courseClass->getCourse()->getId()
        ));
    }

}
