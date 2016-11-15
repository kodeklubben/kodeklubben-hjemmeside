<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class ControlPanelController.
 *
 * @Route("/kontrollpanel")
 */
class ControlPanelController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/", name="control_panel")
     */
    public function showAction()
    {
        return $this->render('@App/control_panel/show.html.twig', array(// ...
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/epost", name="cp_email")
     */
    public function showEmailAction()
    {
        $currentSemester = $this->getDoctrine()->getRepository('AppBundle:Semester')->findCurrentSemester();
        $courses = $this->getDoctrine()->getRepository('CourseBundle:Course')->findBySemester($currentSemester);

        return $this->render('@App/control_panel/show_email.html.twig', array(
            'semester' => $currentSemester,
            'courses' => $courses,
        ));
    }
}
