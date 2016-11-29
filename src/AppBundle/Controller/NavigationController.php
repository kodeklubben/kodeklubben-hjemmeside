<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NavigationController extends Controller
{
    public function navigationAction()
    {
        return $this->render('base/navigation.html.twig');
    }
}
