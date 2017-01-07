<?php

namespace UserBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use UserBundle\Entity\User;
use UserBundle\Form\Type\AdminUserType;

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
     * @Method("GET")
     */
    public function showAction()
    {
        $club = $this->get('club_manager')->getCurrentClub();
        $users = $this->getDoctrine()->getRepository('UserBundle:User')->findByClub($club);

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
     * @Method("GET")
     */
    public function showSpecificAction(User $user)
    {
        $this->get('club_manager')->denyIfNotCurrentClub($user);

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
     * @Method({"GET", "POST"})
     */
    public function createUserAction(Request $request)
    {
        $userRegistration = $this->get('user.registration');
        $user = $userRegistration->newUser();

        $userRegistration->setRandomEncodedPassword($user);

        $form = $this->createForm(AdminUserType::class, $user);
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

    /**
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @Route("/bruker/type",
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

        if (!$this->get('user.roles')->isValidRole($userRole) ||
            $user === $this->getUser()
        ) {
            throw new BadRequestHttpException();
        }

        $this->get('club_manager')->denyIfNotCurrentClub($user);

        $currentUserRole = $user->getRoles()[0];
        //Check if trying to change to current role
        if ($userRole === $currentUserRole) {
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
     * @Route("/bruker/slett",
     *     options = { "expose" = true },
     *     name="cp_user_delete"
     * )
     * @Method({"POST"})
     */
    public function deleteAction(Request $request)
    {
        $userId = $request->request->get('userId');

        if ($userId === null) {
            throw new BadRequestHttpException();
        }

        $this->get('logger')->info("Deleting user with id $userId");

        $user = $this->getDoctrine()->getRepository('UserBundle:User')->find($userId);

        if ($user === null) {
            throw $this->createNotFoundException();
        }

        $this->get('club_manager')->denyIfNotCurrentClub($user);

        if ($user === $this->getUser()) {
            throw new AccessDeniedException('It\'s illegal to kill yourself');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        $this->get('logger')->info("User with name {$user} deleted");

        return new JsonResponse(array('status' => 'success'));
    }

    private function removeCurrentParticipants($user)
    {
        //Remove participation from all courses this and future semesters
        $participants = $this->getDoctrine()->getRepository('CourseBundle:Participant')->findByUserThisAndLaterSemesters($user);
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
        $tutors = $this->getDoctrine()->getRepository('CourseBundle:Tutor')->findByUserThisAndLaterSemesters($user);
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
