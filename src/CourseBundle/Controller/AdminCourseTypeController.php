<?php

namespace CourseBundle\Controller;

use CourseBundle\Entity\CourseType;
use CourseBundle\Form\Type\CourseTypeType;
use ImageBundle\Entity\Image;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AdminCourseTypeController.
 *
 * @Route("/kontrollpanel")
 */
class AdminCourseTypeController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/kurs/type", name="cp_course_type")
     * @Method("GET")
     */
    public function showAction()
    {
        $club = $this->get('club_manager')->getCurrentClub();
        $courses = $this->getDoctrine()->getRepository('CourseBundle:CourseType')->findAllByClub($club);

        return $this->render('@Course/control_panel/show_course_type.html.twig', array(
            'courses' => $courses,
        ));
    }

    /**
     * @param Request         $request
     * @param CourseType|null $courseType
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/kurs/type/ny", name="cp_create_course_type")
     * @Route("/kurs/type/{id}",
     *     requirements={"id" = "\d+"},
     *     name="cp_edit_course_type"
     * )
     * @Method({"GET", "POST"})
     */
    public function editCourseTypeAction(Request $request, CourseType $courseType = null)
    {

        // Check if this is a create or edit
        $isCreate = is_null($courseType);
        if ($isCreate) {
            $club = $this->get('club_manager')->getCurrentClub();

            // Initialize a new CourseType with a new image
            $image = new Image();
            $image->setClub($club);

            $courseType = new CourseType();
            $courseType->setClub($club);
            $courseType->setImage($image);
        } else {
            $this->get('club_manager')->denyIfNotCurrentClub($courseType);
        }

        $form = $this->createForm(CourseTypeType::class, $courseType, array(
            'isCreate' => $isCreate
        ));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Upload image
            $image = $courseType->getImage();
            if (!is_null($image->getFile())) {
                $image->setName($courseType->getName());
                $this->get('app.image_uploader')->uploadImage($image);
            }

            // Save CourseType
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($courseType);
            $manager->flush();

            return $this->redirectToRoute('cp_course_type');
        }

        return $this->render('@Course/control_panel/show_edit_course_type.html.twig', array(
            'courseType' => $courseType,
            'form' => $form->createView(),
        ));
    }

    /**
     * @param CourseType $courseType
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/kurs/type/slett/{id}",
     *     requirements={"id" = "\d+"},
     *     name="cp_delete_course_type"
     * )
     *
     * @Method({"POST"})
     */
    public function deleteCourseTypeAction(CourseType $courseType)
    {
        $this->get('club_manager')->denyIfNotCurrentClub($courseType);

        // Soft delete CourseType
        $courseType->delete();
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($courseType);
        $manager->flush();

        return $this->redirectToRoute('cp_course_type');
    }
}
