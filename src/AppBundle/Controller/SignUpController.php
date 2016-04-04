<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SignUpController extends Controller
{
    public function showAction()
    {
        return $this->render('sign_up/show.html.twig');
    }

}
