<?php

namespace CodeClubAdminBundle\Controller;

use CodeClubBundle\Entity\Course;
use CodeClubBundle\Entity\CourseClass;
use CodeClubAdminBundle\Form\CourseFormType;
use CodeClubAdminBundle\Form\CourseClassType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CourseController extends Controller
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
        $courses = $this->getDoctrine()->getRepository('CodeClubBundle:Course')->findBySemester($semester);
        return $this->render('@CodeClubAdmin/course/show.html.twig', array(
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
        return $this->render('@CodeClubAdmin/course/show_create_course.html.twig', array(
            'course' => $course,
            'form' => $form->createView()
        ));
    }

    public function editTimeTableAction(Request $request, Course $course)
    {
        $courseClass = new CourseClass();
        $latestCourseClass = $this->getDoctrine()->getRepository('CodeClubBundle:CourseClass')->findOneBy(array('course' => $course), array('time' => 'DESC'), 1);

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
        return $this->render('@CodeClubAdmin/course/show_time_table.html.twig', array(
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
        return $this->render('@CodeClubAdmin/course/show_course_participants.html.twig', array('course' => $course));
    }

    public function showTutorsAction(Course $course)
    {
        return $this->render('@CodeClubAdmin/course/show_course_tutors.html.twig', array('course' => $course));
    }

    public function deleteCourseClassAction($id){
        $manager = $this->getDoctrine()->getManager();
        $courseClass = $manager->getRepository('CodeClubBundle:CourseClass')->find($id);
        if(!is_null($courseClass)){
            $manager->remove($courseClass);
            $manager->flush();
        }
        return $this->redirectToRoute('cp_course_time_table', array(
            'id' => $courseClass->getCourse()->getId()
        ));
    }

}
