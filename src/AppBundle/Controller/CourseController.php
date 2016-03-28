<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Course;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CourseController extends Controller
{

    public function showAction()
    {
        $courses = $this->getDoctrine()->getRepository('AppBundle:CourseType')->findAll();
        return $this->render(':courses:index.html.twig', array('courses' => $courses));
    }
}

