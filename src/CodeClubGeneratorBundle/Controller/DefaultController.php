<?php

namespace CodeClubGeneratorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('CodeClubGeneratorBundle:Default:index.html.twig');
    }
}
