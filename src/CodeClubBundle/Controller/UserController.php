<?php

namespace CodeClubBundle\Controller;

use CodeClubBundle\Entity\User;
use CodeClubBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    
    public function showRegistrationOptionsAction()
    {
        return $this->render('@CodeClub/user/registration_options.html.twig');
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
            '@CodeClub/user/registration.html.twig',
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

    public function changeUserTypeAction(Request $request)
    {
        $userId = $request->request->get('userId');
        $role = $request->request->get('role');
        $userRole = 'ROLE_' . strtoupper($role);
        $user = $this->getDoctrine()->getRepository('CodeClubBundle:User')->find($userId);
        $currentUserRole = $user->getRoles()[0];
        //Check if trying to change to current role
        if($userRole == $currentUserRole) return new JsonResponse(array('status' => 'success'));
        
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
                if($userRole === 'ROLE_PARTICIPANT' || $userRole === 'ROLE_PARENT')
                {
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
    
    public function deleteAction(Request $request)
    {
        $userId = $request->request->get('userId');
        $user = $this->getDoctrine()->getRepository('CodeClubBundle:User')->find($userId);
        $isLoggedInUser = $user->getId() === $this->getUser()->getId();
        
        //Clear all connections to courses
        $this->removeTutors($user);
        $this->removeParticipants($user);
        $this->removeChildren($user);
        
        $manager = $this->getDoctrine()->getManager();
        $manager->remove($user);
        $manager->flush();
        
        if($isLoggedInUser)
        {
            //Logout user
            $this->get('security.token_storage')->setToken(null);
            $this->get('request')->getSession()->invalidate();
        }
        return new JsonResponse(array('status' => 'success'));
    }
    
    private function removeCurrentParticipants($user)
    {
        //Remove participation from all courses this and future semesters
        $participants = $this->getDoctrine()->getRepository('CodeClubBundle:Participant')->findByUserThisAndLaterSemesters($user);
        $manager = $this->getDoctrine()->getManager();
        foreach ($participants as $participant)
        {
            $manager->remove($participant);
        }
        $manager->flush();
    }
    
    private function removeCurrentTutors($user)
    {
        //Remove tutor from all courses this and future semesters
        $manager = $this->getDoctrine()->getManager();
        $courses = $this->getDoctrine()->getRepository('CourseBundle:Course')->findByTutorThisAndLaterSemesters($user);
        foreach ($courses as $course) {
            $course->removeTutor($user);
            $manager->persist($course);
        }
        $manager->flush();
    }

    private function removeParticipants($user)
    {
        $participants = $this->getDoctrine()->getRepository('CodeClubBundle:Participant')->findByUserThisAndLaterSemesters($user);
        $manager = $this->getDoctrine()->getManager();
        foreach ($participants as $participant)
        {
            $manager->remove($participant);
        }
        $manager->flush();
    }
    
    private function removeTutors($user)
    {
        $manager = $this->getDoctrine()->getManager();
        $courses = $this->getDoctrine()->getRepository('CourseBundle:Course')->findByTutor($user);
        foreach ($courses as $course) {
            $course->removeTutor($user);
            $manager->persist($course);
        }
        $manager->flush();
    }
    
    private function removeChildren($user)
    {
        $manager = $this->getDoctrine()->getManager();
        $children = $this->getDoctrine()->getRepository('CodeClubBundle:Child')->findBy(array('parent' => $user));
        foreach ($children as $child)
        {
            $manager->remove($child);
        }
        $manager->flush();
    }
    
}
