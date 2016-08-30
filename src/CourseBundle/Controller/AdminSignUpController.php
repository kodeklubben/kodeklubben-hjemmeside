<?php

namespace CourseBundle\Controller;

use UserBundle\Entity\Child;
use CourseBundle\Entity\Course;
use CourseBundle\Entity\CourseType;
use UserBundle\Entity\Participant;
use UserBundle\Entity\Tutor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UserBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

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
     */
    public function showAction(User $user)
    {
        $currentSemester = $this->getDoctrine()->getRepository('CodeClubBundle:Semester')->findCurrentSemester();
        $allCourseTypes = $this->getDoctrine()->getRepository('CourseBundle:CourseType')->findAll();
        $courseTypes = $this->filterActiveCourses($allCourseTypes);
        $parameters = array(
            'currentSemester' => $currentSemester,
            'courseTypes' => $courseTypes,
            'user' => $user,
        );
        if (in_array('ROLE_PARENT', $user->getRoles())) {
            $participants = $this->getDoctrine()->getRepository('UserBundle:Participant')->findBy(array('user' => $user));
            $children = $this->getDoctrine()->getRepository('UserBundle:Child')->findBy(array('parent' => $user));

            return $this->render('@Course/control_panel/sign_up/parent.html.twig', array_merge($parameters, array(
                'participants' => $participants,
                'children' => $children,
            )));
        } elseif (in_array('ROLE_PARTICIPANT', $user->getRoles())) {
            $participants = $this->getDoctrine()->getRepository('UserBundle:Participant')->findBy(array('user' => $user));

            return $this->render('@Course/control_panel/sign_up/participant.html.twig', array_merge($parameters, array(
                'participants' => $participants,
            )));
        } elseif (in_array('ROLE_TUTOR', $user->getRoles())) {
            $tutors = $this->getDoctrine()->getRepository('UserBundle:Tutor')->findBy(array('user' => $user));

            return $this->render('@Course/control_panel/sign_up/tutor.html.twig', array_merge($parameters, array(
                'tutors' => $tutors,
            )));
        } else {
            // This should never happen
            return $this->createAccessDeniedException();
        }
    }

    /**
     * @param Course  $course
     * @param Child   $child
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * 
     * @Route("/pamelding/barn/{id}/{child}",
     *     requirements={"id" = "\d+", "child" = "\d+"},
     *     options={"expose" = true},
     *     name="cp_sign_up_course_child"
     * )
     */
    public function signUpChildAction(Course $course, Child $child, Request $request)
    {
        // Check if child is already signed up to the course or the course is set for another semester
        $isAlreadyParticipant = count($this->getDoctrine()->getRepository('UserBundle:Participant')->findBy(array('course' => $course, 'child' => $child))) > 0;
        $isThisSemester = $course->getSemester()->isEqualTo($this->getDoctrine()->getRepository('CodeClubBundle:Semester')->findCurrentSemester());
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

        //Add user as participant to the course
        $participant = new Participant();
        $participant->setUser($child->getParent());
        $participant->setCourse($course);
        $participant->setFirstName($child->getFirstName());
        $participant->setLastName($child->getLastName());
        $participant->setChild($child);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($participant);
        $manager->flush();

        $flashMessage = 'Du har meldt '.$child->getFirstName().' '.$child->getLastName().' på '.$course->getName();
        $this->addFlash('success', $flashMessage);

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
        $isAlreadyParticipant = count($this->getDoctrine()->getRepository('UserBundle:Participant')->findBy(array('course' => $course, 'user' => $user))) > 0;
        $isAlreadyTutor = count($this->getDoctrine()->getRepository('UserBundle:Tutor')->findBy(array('course' => $course, 'user' => $user))) > 0;
        $isThisSemester = $course->getSemester()->isEqualTo($this->getDoctrine()->getRepository('CodeClubBundle:Semester')->findCurrentSemester());
        if ($isAlreadyParticipant || $isAlreadyTutor || !$isThisSemester) {
            $this->addFlash('warning', 'Du er allerede påmeldt '.$course->getName());

            return $this->redirect($request->headers->get('referer'));
        }

        // Sign up as a participant if the user is logged in as a participant user
        if (in_array('ROLE_PARTICIPANT', $user->getRoles())) {
            //Check if course is full
            if (count($course->getParticipants()) >= $course->getParticipantLimit()) {
                $this->addFlash('warning', $course->getName().' er fullt. '.$user->getFullName().' har derfor IKKE blitt påmeldt.');

                return $this->redirect($request->headers->get('referer'));
            }

            //Add user as participant to the course
            $participant = new Participant();
            $participant->setUser($user);
            $participant->setCourse($course);
            $participant->setFirstName($user->getFirstName());
            $participant->setLastName($user->getLastName());
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($participant);
            $manager->flush();

            $this->addFlash('success', 'Du har meldt '.$user->getFullName().' på '.$course->getName());

            // Sign up as a tutor if the user is logged in as a tutor user
        } elseif (in_array('ROLE_TUTOR', $user->getRoles())) {
            $isSubstitute = !is_null($request->request->get('substitute'));
            $tutor = new Tutor();
            $tutor->setCourse($course);
            $tutor->setUser($user);
            $tutor->setIsSubstitute($isSubstitute);
            $course->addTutor($tutor);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($tutor);
            $manager->persist($course);
            $manager->flush();

            $role = $isSubstitute ? 'vikar' : 'veileder';
            $this->addFlash('success', 'Du har meldt '.$user->getFullName().' på '.$course->getName().' som '.$role);
        } else {
            $this->addFlash('danger', 'Det har skjedd en feil! Vennligst prøv igjen.');
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @param CourseType[] $allCourseTypes
     *
     * @return array
     */
    private function filterActiveCourses($allCourseTypes)
    {
        $currentSemester = $this->getDoctrine()->getRepository('CodeClubBundle:Semester')->findCurrentSemester();
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
