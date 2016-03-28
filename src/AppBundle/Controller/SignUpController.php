<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SignUpController extends Controller
{
    public function showAction()
    {
        $courseSeries = $this->getDoctrine()->getRepository('AppBundle:CourseSeries')->findAll();
        return $this->render('sign_up/show.html.twig', array(
            'courseSeries' => $courseSeries
        ));
    }

}
