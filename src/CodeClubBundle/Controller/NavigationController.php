<?php

namespace CodeClubBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class NavigationController extends Controller
{
    public function navigationAction()
    {
        $response = $this->render('_base/navigation.html.twig');
//        $response->setSharedMaxAge(0);
        return $response;
    }
}
