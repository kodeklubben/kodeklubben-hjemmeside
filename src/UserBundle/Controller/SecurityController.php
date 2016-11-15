<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller used to manage the application security.
 */
class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        // Already logged in
        if ($this->getUser() !== null) {
            return $this->redirectToRoute('home');
        }

        $helper = $this->get('security.authentication_utils');
        $last_username = $helper->getLastUsername();
        if (!$last_username) {
            $last_username = $request->get('last_username');
        }

        return $this->render('@User/login.html.twig', array(
            // last username entered by the user (if any)
            'last_username' => $last_username,
            // last authentication error (if any)
            'error' => $helper->getLastAuthenticationError(),
        ));
    }

    /**
     * This is the route the login form submits to.
     *
     * But, this will never be executed. Symfony will intercept this first
     * and handle the login automatically. See form_login in app/config/security.yml
     */
    public function loginCheckAction()
    {
        throw new \Exception('This should never be reached!');
    }

    /**
     * This is the route the user can use to logout.
     *
     * But, this will never be executed. Symfony will intercept this first
     * and handle the logout automatically. See logout in app/config/security.yml
     */
    public function logoutAction()
    {
        throw new \Exception('This should never be reached!');
    }
}
