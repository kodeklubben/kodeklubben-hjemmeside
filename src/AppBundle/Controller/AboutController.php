<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AboutController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/om", name="about")
     * @Method("GET")
     */
    public function showAction()
    {
        return $this->render('@App/about/show.html.twig');
    }
}
