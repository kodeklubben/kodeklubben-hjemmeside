<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    
    public function showRegistrationOptionsAction()
    {
        return $this->render('user/registrationOptions.html.twig');
    }

    public function registerParticipantAction(Request $request)
    {
        return $this->registerUser('ROLE_PARTICIPANT','security_login_form', $request);
    }

    public function registerParentAction(Request $request)
    {
        return $this->registerUser('ROLE_PARENT','security_login_form', $request);
    }

    public function registerTutorAction(Request $request)
    {
        return $this->registerUser('ROLE_TUTOR','security_login_form', $request);
    }


    public function registerAdminAction(Request $request)
    {
        return $this->registerUser('ROLE_ADMIN','control_panel', $request);
    }

    /**
     * @param string $role
     * @param string $redirectRoute
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function registerUser($role, $redirectRoute, Request $request){
        $user = new User();
        $user->setRoles(array($role));
        $form = $this->createForm(new UserType(), $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleRegistrationForm($user);
            return $this->redirectToRoute($redirectRoute);
        }

        $roleTranslate = array(
            'ROLE_PARTICIPANT' => 'Deltaker',
            'ROLE_PARENT' => 'Foresatt',
            'ROLE_TUTOR' => 'Veileder',
            'ROLE_ADMIN' => 'Admin'
        );
        return $this->render(
            'user/registration.html.twig',
            array('form' => $form->createView(), 'role' => $roleTranslate[$role])
        );
    }

    /**
     * @param User $user
     * 
     * Encrypts password and persists user to database
     */
    private function handleRegistrationForm(User $user)
    {
        $password = $this->get('security.password_encoder')
            ->encodePassword($user, $user->getPassword());
        $user->setPassword($password);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
    }
}
