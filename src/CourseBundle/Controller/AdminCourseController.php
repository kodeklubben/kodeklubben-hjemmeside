<?php

namespace CourseBundle\Controller;

use CourseBundle\Entity\Course;
use CourseBundle\Entity\CourseClass;
use CourseBundle\Form\Type\CourseClassType;
use CourseBundle\Form\Type\CourseFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class AdminCourseController.
 *
 * @Route("/kontrollpanel")
 */
class AdminCourseController extends Controller
{
    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/kurs", name="cp_course")
     */
    public function showAction(Request $request)
    {
        $semesterId = $request->query->get('semester');
        $semesterRepo = $this->getDoctrine()->getRepository('AppBundle:Semester');
        if (!is_null($semesterId)) {
            $semester = $semesterRepo->find($semesterId);
        } else {
            $semester = $semesterRepo->findCurrentSemester();
        }
        $semesters = $semesterRepo->findAll();
        $courses = $this->getDoctrine()->getRepository('CourseBundle:Course')->findBySemester($semester);

        return $this->render('@Course/control_panel/show.html.twig', array(
            'courses' => $courses,
            'semester' => $semester,
            'semesters' => $semesters,
        ));
    }

    /**
     * @param Request     $request
     * @param Course|null $course
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/kurs/ny", name="cp_create_course")
     * @Route("/kurs/{id}", name="cp_edit_course", requirements={"id" = "\d+"})
     */
    public function editCourseAction(Request $request, Course $course = null)
    {
        $isCreateAction = is_null($course);
        if ($isCreateAction) {
            $course = new Course();
        }
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
            'form' => $form->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param Course  $course
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/kurs/timeplan/{id}",
     *     requirements={"id" = "\d+"},
     *     name="cp_course_time_table"
     * )
     */
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
            'form' => $form->createView(),
        ));
    }

    /**
     * @param Course $course
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/kurs/slett/{id}",
     *     requirements={"id" = "\d+"},
     *     name="cp_delete_course"
     * )
     */
    public function deleteCourseAction(Course $course)
    {
        $course->delete();
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($course);
        $manager->flush();

        return $this->redirectToRoute('cp_course');
    }

    /**
     * @param Course $course
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/kurs/deltakere/{id}",
     *     requirements={"id" = "\d+"},
     *     name="cp_course_participants"
     * )
     */
    public function showParticipantsAction(Course $course)
    {
        return $this->render('@Course/control_panel/show_course_participants.html.twig', array('course' => $course));
    }

    /**
     * @param Course $course
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/kurs/veiledere/{id}",
     *     requirements={"id" = "\d+"},
     *     name="cp_course_tutors"
     * )
     */
    public function showTutorsAction(Course $course)
    {
        return $this->render('@Course/control_panel/show_course_tutors.html.twig', array('course' => $course));
    }

    /**
     * @param $id
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/kontrollpanel/kursdag/slett/{id}",
     *     requirements={"id" = "\d+"},
     *     name="cp_delete_course_class"
     * )
     */
    public function deleteCourseClassAction($id)
    {
        $manager = $this->getDoctrine()->getManager();
        $courseClass = $manager->getRepository('CourseBundle:CourseClass')->find($id);
        if (!is_null($courseClass)) {
            $manager->remove($courseClass);
            $manager->flush();
        }

        return $this->redirectToRoute('cp_course_time_table', array(
            'id' => $courseClass->getCourse()->getId(),
        ));
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/veiledere", name="cp_tutors")
     */
    public function showAllTutorsAction(Request $request)
    {
        return $this->renderCourseUsers($request, '@Course/control_panel/show_tutors.html.twig');
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/deltakere", name="cp_participants")
     */
    public function showAllParticipantsAction(Request $request)
    {
        return $this->renderCourseUsers($request, '@Course/control_panel/show_participants.html.twig');
    }

    private function renderCourseUsers(Request $request, $template)
    {
        $semesterId = $request->query->get('semester');
        $semesterRepo = $this->getDoctrine()->getRepository('AppBundle:Semester');
        $semester = is_null($semesterId) ? $semesterRepo->findCurrentSemester() : $semesterRepo->find($semesterId);
        $courses = $this->getDoctrine()->getRepository('CourseBundle:Course')->findBySemester($semester);
        $semesters = $semesterRepo->findAll();

        return $this->render($template, array(
            'courses' => $courses,
            'semester' => $semester,
            'semesters' => $semesters,
        ));
    }
}
