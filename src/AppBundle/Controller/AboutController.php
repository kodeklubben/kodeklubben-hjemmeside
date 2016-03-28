<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AboutController extends Controller
{
    public function showAction()
    {
        return $this->render('about/show.html.twig');
    }

}
