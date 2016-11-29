<?php

namespace UserBundle\Controller;

use UserBundle\Entity\User;
use UserBundle\Form\Type\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

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
        $user = new User();
        $user->setRoles(array($role));
        $user->setClub($this->get('club_manager')->getCurrentClub());
        $form = $this->createForm(new UserType(), $user);

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

        $form = $this->createForm(new UserType(), $user);
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
     *
     * @return JsonResponse
     *
     * @Route("/kontrollpanel/bruker/type",
     *     options = { "expose" = true },
     *     name="cp_change_user_type"
     * )
     * @Method({"POST"})
     */
    public function changeUserTypeAction(Request $request)
    {
        $userId = $request->request->get('userId');
        $role = $request->request->get('role');
        $userRole = 'ROLE_'.strtoupper($role);
        $user = $this->getDoctrine()->getRepository('UserBundle:User')->find($userId);
        $currentUserRole = $user->getRoles()[0];
        //Check if trying to change to current role
        if ($userRole == $currentUserRole) {
            return new JsonResponse(array('status' => 'success'));
        }

        $manager = $this->getDoctrine()->getManager();

        switch ($currentUserRole) {
            case 'ROLE_PARENT':
                //Remove participation from all courses this and future semesters
                $this->removeCurrentParticipants($user);
                //Remove all children
                $this->removeChildren($user);
                break;
            case 'ROLE_PARTICIPANT':
                //Remove participation from all courses this and future semesters
                $this->removeCurrentParticipants($user);
                break;

            case 'ROLE_ADMIN':
            case 'ROLE_TUTOR':
                //If user is changed to Participant or Parent
                if ($userRole === 'ROLE_PARTICIPANT' || $userRole === 'ROLE_PARENT') {
                    $this->removeCurrentTutors($user);
                }
        }
        //Update user role
        $user->removeRoles();
        $user->addRole($userRole);
        $manager->persist($user);

        $manager->flush();

        return new JsonResponse(array('status' => 'success'));
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @Route("/kontrollpanel/buker/slett",
     *     options = { "expose" = true },
     *     name="cp_user_delete"
     * )
     * @Method({"POST"})
     */
    public function deleteAction(Request $request)
    {
        $userId = $request->request->get('userId');
        $user = $this->getDoctrine()->getRepository('UserBundle:User')->find($userId);
        $isLoggedInUser = $user->getId() === $this->getUser()->getId();

        //Clear all connections to courses
        $this->removeTutors($user);
        $this->removeParticipants($user);
        $this->removeChildren($user);

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($user);
        $manager->flush();

        if ($isLoggedInUser) {
            //Logout user
            $this->get('security.token_storage')->setToken(null);
            $this->get('request')->getSession()->invalidate();
        }

        return new JsonResponse(array('status' => 'success'));
    }

    private function removeCurrentParticipants($user)
    {
        //Remove participation from all courses this and future semesters
        $participants = $this->getDoctrine()->getRepository('UserBundle:Participant')->findByUserThisAndLaterSemesters($user);
        $manager = $this->getDoctrine()->getManager();
        foreach ($participants as $participant) {
            $manager->remove($participant);
        }
        $manager->flush();
    }

    private function removeCurrentTutors($user)
    {
        //Remove tutor from all courses this and future semesters
        $manager = $this->getDoctrine()->getManager();
        $tutors = $this->getDoctrine()->getRepository('UserBundle:Tutor')->findByUserThisAndLaterSemesters($user);
        foreach ($tutors as $tutor) {
            $manager->remove($tutor);
        }
        $manager->flush();
    }

    private function removeParticipants($user)
    {
        $participants = $this->getDoctrine()->getRepository('UserBundle:Participant')->findByUser($user);
        $manager = $this->getDoctrine()->getManager();
        foreach ($participants as $participant) {
            $manager->remove($participant);
        }
        $manager->flush();
    }

    private function removeTutors($user)
    {
        $tutors = $this->getDoctrine()->getRepository('UserBundle:Tutor')->findByUser($user);
        $manager = $this->getDoctrine()->getManager();
        foreach ($tutors as $tutor) {
            $manager->remove($tutor);
        }
        $manager->flush();
    }

    private function removeChildren($user)
    {
        $manager = $this->getDoctrine()->getManager();
        $children = $this->getDoctrine()->getRepository('UserBundle:Child')->findBy(array('parent' => $user));
        foreach ($children as $child) {
            $manager->remove($child);
        }
        $manager->flush();
    }
}
