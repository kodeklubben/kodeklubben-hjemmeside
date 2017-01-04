<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NavigationController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function navigationAction()
    {
        return $this->render('base/navigation.html.twig');
    }
}
