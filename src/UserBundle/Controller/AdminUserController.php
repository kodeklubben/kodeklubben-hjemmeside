<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;
use UserBundle\Form\Type\AdminUserType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class AdminUserController.
 *
 * @Route("/kontrollpanel")
 */
class AdminUserController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/brukere", name="cp_users")
     */
    public function showAction()
    {
        $users = $this->getDoctrine()->getRepository('UserBundle:User')->findAll();

        return $this->render('@User/control_panel/show_users.html.twig', array(
            'users' => $users,
        ));
    }

    /**
     * @param User $user
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/brukere/{id}", name="cp_user")
     */
    public function showSpecificAction(User $user)
    {
        return $this->render('@User/control_panel/user_specific.html.twig', array(
            'user' => $user,
        ));
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * 
     * @Route("/bruker/ny", name="cp_user_create")
     */
    public function createUserAction(Request $request)
    {
        $userRegistration = $this->get('user.registration');
        $user = $userRegistration->newUser();

        $userRegistration->setRandomEncodedPassword($user);

        $form = $this->createForm(new AdminUserType(), $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $role = $form['role']->getData();
            if ($this->get('user.roles')->isValidRole($role)) {
                $user->setRoles([$role]);
                $userRegistration->persistUserAndSendNewUserCode($user);
            }

            return $this->redirectToRoute('cp_users');
        }

        return $this->render('@User/control_panel/create_user.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
