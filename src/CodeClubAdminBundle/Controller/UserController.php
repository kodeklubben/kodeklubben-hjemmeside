<?php

namespace CodeClubAdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    public function showAction()
    {
        $users = $this->getDoctrine()->getRepository('CodeClubBundle:User')->findAll();
        return $this->render('@CodeClubAdmin/user/show_users.html.twig', array(
            'users' => $users
        ));
    }

}
