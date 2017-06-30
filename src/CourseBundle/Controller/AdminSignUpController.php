<?php

namespace CourseBundle\Controller;

use CourseBundle\Entity\Course;
use CourseBundle\Entity\CourseType;
use CourseBundle\Event\ParticipantDeletedEvent;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use CourseBundle\Entity\Participant;
use CourseBundle\Entity\Tutor;
use UserBundle\Entity\User;

/**
 * Class AdminSignUpController.
 *
 * @Route("/kontrollpanel")
 */
class AdminSignUpController extends Controller
{
    /**
     * @param User $user
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Symfony\Component\Security\Core\Exception\AccessDeniedException
     *
     * @Route("/pamelding/{id}",
     *     requirements={"id" = "\d+"},
     *     name="cp_sign_up"
     * )
     * @Method("GET")
     */
    public function showAction(User $user)
    {
        $this->get('club_manager')->denyIfNotCurrentClub($user);
        $currentSemester = $this->getDoctrine()->getRepository('AppBundle:Semester')->findCurrentSemester();
        $club = $this->get('club_manager')->getCurrentClub();
        $allCourseTypes = $this->getDoctrine()->getRepository('CourseBundle:CourseType')->findAllByClub($club);
        $courseTypes = $this->filterActiveCourses($allCourseTypes);
        $parameters = array(
            'currentSemester' => $currentSemester,
            'courseTypes' => $courseTypes,
            'user' => $user,
        );
        if (in_array('ROLE_PARENT', $user->getRoles())) {
            $participants = $this->getDoctrine()->getRepository('CourseBundle:Participant')->findBy(array('user' => $user));
            $children = $this->getDoctrine()->getRepository('UserBundle:Child')->findBy(array('parent' => $user));

            return $this->render('@Course/control_panel/sign_up/parent.html.twig', array_merge($parameters, array(
                'participants' => $participants,
                'children' => $children,
            )));
        } elseif (in_array('ROLE_PARTICIPANT', $user->getRoles())) {
            $participants = $this->getDoctrine()->getRepository('CourseBundle:Participant')->findBy(array('user' => $user));

            return $this->render('@Course/control_panel/sign_up/participant.html.twig', array_merge($parameters, array(
                'participants' => $participants,
            )));
        } elseif (in_array('ROLE_TUTOR', $user->getRoles())) {
            $tutors = $this->getDoctrine()->getRepository('CourseBundle:Tutor')->findBy(array('user' => $user));

            return $this->render('@Course/control_panel/sign_up/tutor.html.twig', array_merge($parameters, array(
                'tutors' => $tutors,
            )));
        } else {
            return $this->redirectToRoute('cp_users');
        }
    }

    /**
     * @param Course  $course
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/pamelding/barn/{id}",
     *     requirements={"id" = "\d+"},
     *     name="cp_sign_up_course_child"
     * )
     * @Method("POST")
     */
    public function signUpChildAction(Course $course, Request $request)
    {
        $this->get('club_manager')->denyIfNotCurrentClub($course);

        $childId = $request->request->get('child');
        $child = $this->getDoctrine()->getRepository('UserBundle:Child')->find($childId);
        $this->get('club_manager')->denyIfNotCurrentClub($child);
        if ($child === null) {
            throw new NotFoundHttpException('Child not found');
        }
        // Check if child is already signed up to the course or the course is set for another semester
        $isAlreadyParticipant = count($this->getDoctrine()->getRepository('CourseBundle:Participant')->findBy(array('course' => $course, 'child' => $child))) > 0;
        $isThisSemester = $course->getSemester()->isEqualTo($this->getDoctrine()->getRepository('AppBundle:Semester')->findCurrentSemester());
        if ($isAlreadyParticipant || !$isThisSemester) {
            if ($isAlreadyParticipant) {
                $this->addFlash('warning', $child.' er allerede påmeldt '.$course->getName().'. Ingen handling har blitt utført');
            } elseif (!$isThisSemester) {
                $this->addFlash('danger', 'Det har skjedd en feil, vennligst prøv igjen. Kontakt oss hvis problemet vedvarer');
            }

            return $this->redirect($request->headers->get('referer'));
        }
        //Check if course is full
        if (count($course->getParticipants()) >= $course->getParticipantLimit()) {
            $this->addFlash('warning', $course->getName().' er fullt. '.$child.' har IKKE blitt påmeldt');

            return $this->redirect($request->headers->get('referer'));
        }

        $participant = $this->get('course.sign_up')->createParticipant($course, $child->getParent(), $child);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($participant);
        $manager->flush();

        $flashMessage = 'Du har meldt '.$child->getFirstName().' '.$child->getLastName().' på '.$course->getName();
        $this->addFlash('success', $flashMessage);

        $this->get('course.queue_manager')->promoteParticipantsFromQueueToCourse($course);

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @param Course  $course
     * @param User    $user
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/pamelding/{course}/{user}",
     *     requirements={"user" = "\d+"},
     *     name="cp_sign_up_course"
     * )
     * @Method({"POST"})
     */
    public function signUpAction(Course $course, User $user, Request $request)
    {
        // Check if user is already signed up to the course or the course is set for another semester
        $isAlreadyParticipant = count($this->getDoctrine()->getRepository('CourseBundle:Participant')->findBy(array('course' => $course, 'user' => $user))) > 0;
        $isAlreadyTutor = count($this->getDoctrine()->getRepository('CourseBundle:Tutor')->findBy(array('course' => $course, 'user' => $user))) > 0;
        $isThisSemester = $course->getSemester()->isEqualTo($this->getDoctrine()->getRepository('AppBundle:Semester')->findCurrentSemester());
        if ($isAlreadyParticipant || $isAlreadyTutor || !$isThisSemester) {
            $this->addFlash('warning', 'Du er allerede påmeldt '.$course->getName());

            return $this->redirect($request->headers->get('referer'));
        }

        // Sign up as a participant if the user is logged in as a participant user
        if (in_array('ROLE_PARTICIPANT', $user->getRoles())) {
            return $this->signUpParticipant($request, $course, $user);

        // Sign up as a tutor if the user is logged in as a tutor user
        } elseif (in_array('ROLE_TUTOR', $user->getRoles())) {
            return $this->signUpTutor($request, $course, $user);
        } else {
            $this->addFlash('danger', 'Det har skjedd en feil! Vennligst prøv igjen.');
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @param Request $request
     * @param Course  $course
     * @param User    $user
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function signUpParticipant(Request $request, Course $course, User $user)
    {
        $this->get('club_manager')->denyIfNotCurrentClub($course);
        $this->get('club_manager')->denyIfNotCurrentClub($user);

        //Check if course is full
        if (count($course->getParticipants()) >= $course->getParticipantLimit()) {
            $this->addFlash('warning', $course->getName().' er fullt. '.$user->getFullName().' har derfor IKKE blitt påmeldt.');

            return $this->redirect($request->headers->get('referer'));
        }

        //Add user as participant to the course
        $participant = $this->get('course.sign_up')->createParticipant($course, $user);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($participant);
        $manager->flush();

        $this->addFlash('success', 'Du har meldt '.$user->getFullName().' på '.$course->getName());

        $this->get('course.queue_manager')->promoteParticipantsFromQueueToCourse($course);

        return $this->redirect($request->headers->get('referer'));
    }

    private function signUpTutor(Request $request, Course $course, User $user)
    {
        $this->get('club_manager')->denyIfNotCurrentClub($course);
        $this->get('club_manager')->denyIfNotCurrentClub($user);

        $isSubstitute = !is_null($request->request->get('substitute'));
        $tutor = $this->get('course.sign_up')->createTutor($course, $user, $isSubstitute);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($tutor);
        $manager->flush();

        $role = $isSubstitute ? 'vikar' : 'veileder';
        $this->addFlash('success', 'Du har meldt '.$user->getFullName().' på '.$course->getName().' som '.$role);

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @param Participant $participant
     * @param Request     $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/pamelding/deltaker/meldav/{id}",
     *     requirements={"id" = "\d+"},
     *     name="course_admin_withdraw_participant"
     * )
     * @Method("POST")
     */
    public function withdrawParticipantAction(Participant $participant, Request $request)
    {
        $this->get('club_manager')->denyIfNotCurrentClub($participant);

        $name = $participant->getChild() === null ? $participant->getFullName() : $participant->getChild()->getFullName();

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($participant);
        $manager->flush();

        $this->get('event_dispatcher')->dispatch(ParticipantDeletedEvent::NAME, new ParticipantDeletedEvent($participant));

        $this->addFlash('success', 'Du har meldt '.$name.' av '.$participant->getCourse()->getName());

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @param Tutor   $tutor
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/pamelding/veileder/meldav/{id}",
     *     requirements={"id" = "\d+"},
     *     name="course_admin_withdraw_tutor"
     * )
     * @Method("POST")
     */
    public function withdrawTutorAction(Tutor $tutor, Request $request)
    {
        $this->get('club_manager')->denyIfNotCurrentClub($tutor);

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($tutor);
        $manager->flush();

        $this->addFlash('success', 'Du har meldt '.$tutor->getFullName().' av '.$tutor->getCourse()->getName());

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @param CourseType[] $allCourseTypes
     *
     * @return array
     */
    private function filterActiveCourses($allCourseTypes)
    {
        $currentSemester = $this->getDoctrine()->getRepository('AppBundle:Semester')->findCurrentSemester();
        $res = array();
        foreach ($allCourseTypes as $courseType) {
            foreach ($courseType->getCourses() as $course) {
                if ($course->getSemester()->isEqualTo($currentSemester) && !$course->isDeleted()) {
                    $courseTypeName = $courseType->getName();
                    if (!key_exists($courseTypeName, $res)) {
                        $res[$courseTypeName] = array();
                    }
                    $res[$courseTypeName][] = $course;
                }
            }
        }

        return $res;
    }
}
