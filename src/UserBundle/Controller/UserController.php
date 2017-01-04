<?php

namespace UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;
use UserBundle\Form\Type\NewPasswordType;
use UserBundle\Form\Type\UserInfoType;
use UserBundle\Form\Type\UserType;

class UserController extends Controller
{
    private $roleTranslate = array(
        'ROLE_PARTICIPANT' => 'Deltaker',
        'ROLE_PARENT' => 'Foresatt',
        'ROLE_TUTOR' => 'Veileder',
        'ROLE_ADMIN' => 'Admin',
    );
    public function showRegistrationOptionsAction()
    {
        return $this->render('@User/registration_options.html.twig');
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/registrer/deltaker", name="participant_registration")
     * @Method({"GET", "POST"})
     */
    public function registerParticipantAction(Request $request)
    {
        return $this->registerUser('ROLE_PARTICIPANT', $request);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/registrer/foresatt", name="parent_registration")
     * @Method({"GET", "POST"})
     */
    public function registerParentAction(Request $request)
    {
        return $this->registerUser('ROLE_PARENT', $request);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/registrer/veileder", name="tutor_registration")
     * @Method({"GET", "POST"})
     */
    public function registerTutorAction(Request $request)
    {
        return $this->registerUser('ROLE_TUTOR', $request);
    }

    /**
     * @param string  $role
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function registerUser($role, Request $request)
    {
        $user = $this->get('user.registration')->newUser();
        $user->setRoles(array($role));
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->handleRegistrationForm($user);

            return $this->redirectToRoute('security_login_form', array('last_username' => $user->getUsername()));
        }

        return $this->render(
            '@User/registration.html.twig',
            array('form' => $form->createView(), 'role' => $this->roleTranslate[$role])
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

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/registrer/{code}", name="registration_new_user_code")
     * @Method({"GET", "POST"})
     */
    public function registerWithNewUserCodeAction(Request $request)
    {
        $userRegistration = $this->get('user.registration');
        $code = $request->get('code');
        $hashedCode = $userRegistration->hashNewUserCode($code);
        $user = $this->getDoctrine()->getRepository('UserBundle:User')->findOneBy(array('newUserCode' => $hashedCode));
        if (!$user) {
            return $this->render('base/error.html.twig', array(
            'error' => 'Ugyldig kode eller brukeren er allerede opprettet',
            ));
        } else {
            // Force user to create new password
            $user->setPassword(null);
        }
        $this->get('club_manager')->denyIfNotCurrentClub($user);

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setNewUserCode(null);
            $user->setPassword($userRegistration->encodePassword($user, $form['password']->getData()));
            $userRegistration->persistUser($user);

            return $this->redirectToRoute('security_login_form');
        }

        return $this->render('@User/registration.html.twig', array(
            'form' => $form->createView(),
            'role' => $this->roleTranslate[$user->getRoles()[0]],
        ));
    }

    /**
     * @param Request $request
     * @Route("/bruker/endre", name="user_update")
     * @Method({"GET", "POST"})
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editUserAction(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(UserInfoType::class, $user);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Kontoinnstillingene ble lagret.');

            return $this->redirectToRoute('user_update');
        }

        return $this->render('@User/user/update_user_info.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @param Request $request
     * @Route("/bruker/passord", name="user_update_password")
     * @Method({"GET", "POST"})
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editPasswordAction(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(NewPasswordType::class);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $hashedPassword = $this->get('security.password_encoder')->encodePassword($user, $form->get('password')->getData());
            $user->setPassword($hashedPassword);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Passordet ble endret.');

            return $this->redirectToRoute('user_update_password');
        }

        return $this->render('@User/user/update_password.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
