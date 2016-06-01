<?php

namespace CodeClubAdminBundle\Controller;

use CodeClubAdminBundle\Form\CourseTypeType;
use CodeClubBundle\Entity\CourseType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CourseTypeController extends Controller
{
    public function showAction()
    {
        $courses = $this->getDoctrine()->getRepository('CodeClubBundle:CourseType')->findAll();
        return $this->render('@CodeClubAdmin/course/show_course_type.html.twig', array(
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
        return $this->render('@CodeClubAdmin/course/show_edit_course_type.html.twig', array(
            'courseType' => $courseType,
            'form' => $form->createView()
        ));
    }

    public function deleteCourseTypeAction(CourseType $courseType)
    {
        $courseType->delete();
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($courseType);
        $manager->flush();
        return $this->redirectToRoute('cp_course_type');
    }

}
