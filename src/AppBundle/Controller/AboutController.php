<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AboutController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/om", name="about")
     */
    public function showAction()
    {
        $response = $this->render('@App/about/show.html.twig');

        return $response;
    }
}
