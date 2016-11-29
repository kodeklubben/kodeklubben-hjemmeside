<?php

namespace CourseBundle\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use CourseBundle\Entity\Course;
use CourseBundle\Entity\CourseType;
use UserBundle\Entity\Participant;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class SignUpController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/pamelding", name="sign_up")
     * @Method("GET")
     */
    public function showAction()
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_PARENT')) {
            return $this->showParentAction();
        } elseif ($this->get('security.authorization_checker')->isGranted('ROLE_PARTICIPANT')) {
            return $this->showParticipantAction();
        } elseif ($this->get('security.authorization_checker')->isGranted('ROLE_TUTOR')) {
            return $this->showTutorAction();
        } else {
            // This should never happen
            throw new AccessDeniedException();
        }
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function showParticipantAction()
    {
        $currentSemester = $this->getDoctrine()->getRepository('AppBundle:Semester')->findCurrentSemester();
        $club = $this->get('club_manager')->getCurrentClub();
        $allCourseTypes = $this->getDoctrine()->getRepository('CourseBundle:CourseType')->findAllByClub($club);
        $courseTypes = $this->filterActiveCourses($allCourseTypes);
        $user = $this->getUser();
        $participants = $this->getDoctrine()->getRepository('UserBundle:Participant')->findBy(array('user' => $user));

        return $this->render('@Course/sign_up/participant.html.twig', array(
            'currentSemester' => $currentSemester,
            'courseTypes' => $courseTypes,
            'user' => $user,
            'participants' => $participants,
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function showParentAction()
    {
        $currentSemester = $this->getDoctrine()->getRepository('AppBundle:Semester')->findCurrentSemester();
        $club = $this->get('club_manager')->getCurrentClub();
        $allCourseTypes = $this->getDoctrine()->getRepository('CourseBundle:CourseType')->findAllByClub($club);
        $courseTypes = $this->filterActiveCourses($allCourseTypes);
        $user = $this->getUser();

        $participants = $this->getDoctrine()->getRepository('UserBundle:Participant')->findByUser($user);
        $children = $this->getDoctrine()->getRepository('UserBundle:Child')->findByParent($user);

        return $this->render('@Course/sign_up/parent.html.twig', array(
            'currentSemester' => $currentSemester,
            'courseTypes' => $courseTypes,
            'user' => $user,
            'participants' => $participants,
            'children' => $children,
        ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function showTutorAction()
    {
        $currentSemester = $this->getDoctrine()->getRepository('AppBundle:Semester')->findCurrentSemester();
        $club = $this->get('club_manager')->getCurrentClub();
        $allCourseTypes = $this->getDoctrine()->getRepository('CourseBundle:CourseType')->findAllByClub($club);
        $courseTypes = $this->filterActiveCourses($allCourseTypes);
        $user = $this->getUser();
        $tutors = $this->getDoctrine()->getRepository('UserBundle:Tutor')->findBy(array('user' => $user));

        return $this->render('@Course/sign_up/tutor.html.twig', array(
            'currentSemester' => $currentSemester,
            'courseTypes' => $courseTypes,
            'user' => $user,
            'tutors' => $tutors,
        ));
    }

    /**
     * @param Course  $course
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @internal param Child $child
     *
     * @Route("/pamelding/barn/{id}",
     *     options={"expose"=true},
     *     requirements={"id"="\d+"},
     *     name="sign_up_course_child"
     * )
     * @Method("POST")
     */
    public function signUpChildAction(Course $course, Request $request)
    {
        $childId = $request->get('child');
        if ($childId === null) {
            throw new NotFoundHttpException();
        }
        $child = $this->getDoctrine()->getRepository('UserBundle:Child')->find($childId);
        if (!$child->getParent() === $this->getUser()) {
            throw new AccessDeniedException();
        }

        // Check if child is already signed up to the course or the course is set for another semester
        $isAlreadyParticipant = $this->getDoctrine()->getRepository('UserBundle:Participant')->findByCourseAndChild($course, $child) !== null;
        $isThisSemester = $course->getSemester()->isEqualTo($this->getDoctrine()->getRepository('AppBundle:Semester')->findCurrentSemester());
        if ($isAlreadyParticipant) {
            $this->addFlash('warning', $child.' er allerede påmeldt '.$course->getName().'. Ingen handling har blitt utført');

            return $this->redirectToRoute('sign_up');
        } elseif (!$isThisSemester) {
            $this->addFlash('danger', 'Det har skjedd en feil, vennligst prøv igjen. Kontakt oss hvis problemet vedvarer');

            return $this->redirectToRoute('sign_up');
        }

        //Check if course is full
        if (count($course->getParticipants()) >= $course->getParticipantLimit()) {
            $this->addFlash('warning', $course->getName().' er fullt. '.$child.' har IKKE blitt påmeldt');

            return $this->redirectToRoute('sign_up');
        }

        //Add child as participant to the course
        $participant = $this->get('course.sign_up')->createParticipant($course, $child->getParent(), $child);
        $manager = $this->getDoctrine()->getManager();
        $manager->persist($participant);
        $manager->flush();

        $this->addFlash('success', 'Du har meldt '.$child->getFirstName().' '.$child->getLastName().' på '.$course->getName());

        return $this->redirectToRoute('sign_up');
    }

    /**
     * @param Course  $course
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * @Route("/pamelding/{id}", name="sign_up_course", requirements={"id"="\d+"})
     * @Method("POST")
     */
    public function signUpAction(Course $course, Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_PARTICIPANT')) {
            return $this->signUpParticipantAction($course);
        } elseif ($this->get('security.authorization_checker')->isGranted('ROLE_TUTOR')) {
            return $this->signUpTutorAction($course, $request);
        } else {
            $this->addFlash('danger', 'Det har skjedd en feil! Vennligst prøv igjen.');
        }

        return $this->redirectToRoute('sign_up');
    }

    /**
     * @param Course $course
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function signUpParticipantAction(Course $course)
    {
        $user = $this->getUser();
        $isThisSemester = $course->getSemester()->isEqualTo($this->getDoctrine()->getRepository('AppBundle:Semester')->findCurrentSemester());
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_PARTICIPANT') || !$isThisSemester) {
            throw new AccessDeniedException();
        }
        // Check if user is already signed up to the course or the course is set for another semester
        $isAlreadyParticipant = count($this->getDoctrine()->getRepository('UserBundle:Participant')->findBy(array('course' => $course, 'user' => $user))) > 0;
        if ($isAlreadyParticipant) {
            $this->addFlash('warning', 'Du er allerede påmeldt '.$course->getName());

        //Check if course is full
        } elseif (count($course->getParticipants()) >= $course->getParticipantLimit()) {
            $this->addFlash('warning', $course->getName().' er fullt. Du har derfor IKKE blitt påmeldt.');

        //Add user as participant to the course
        } else {
            $participant = $this->get('course.sign_up')->createParticipant($course, $user);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($participant);
            $manager->flush();

            $this->addFlash('success', 'Du har meldt deg på '.$course->getName());
        }

        return $this->redirectToRoute('sign_up');
    }

    /**
     * @param Course  $course
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function signUpTutorAction(Course $course, Request $request)
    {
        $user = $this->getUser();
        $isThisSemester = $course->getSemester()->isEqualTo($this->getDoctrine()->getRepository('AppBundle:Semester')->findCurrentSemester());
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_TUTOR') || !$isThisSemester) {
            throw new AccessDeniedException();
        }
        // Check if user is already signed up to the course
        $isAlreadyTutor = $this->getDoctrine()->getRepository('UserBundle:Tutor')->findByCourseAndUser($course, $user) !== null;
        if ($isAlreadyTutor) {
            $this->addFlash('warning', 'Du er allerede påmeldt '.$course->getName());
        } else {
            $isSubstitute = $request->request->get('substitute') !== null;
            $tutor = $this->get('course.sign_up')->createTutor($course, $user, $isSubstitute);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($tutor);
            $manager->flush();

            $role = $isSubstitute ? 'vikar' : 'veileder';
            $this->addFlash('success', 'Du har meldt deg på '.$course->getName().' som '.$role);
        }

        return $this->redirectToRoute('sign_up');
    }

    /**
     * @param Course  $course
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/pamelding/veileder/meldav/{id}",
     *     requirements={"id" = "\d+"},
     *     name="withdraw_from_course_tutor"
     * )
     * @Method("POST")
     */
    public function withdrawTutorAction(Course $course, Request $request)
    {
        $tutorRepo = $this->getDoctrine()->getRepository('UserBundle:Tutor');
        $user = $this->getUser();

        $tutor = $tutorRepo->findOneBy(array('user' => $user, 'course' => $course));

        // Check if tutor is already signed up to the course
        $isAlreadyTutor = $tutor->getCourse()->getId() === $course->getId();
        if ($isAlreadyTutor) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($tutor);
            $manager->flush();

            $this->addFlash('success', 'Du har meldt deg av '.$course->getName());
        } else {
            $this->addFlash('danger', 'Det skjedde en feil da vi prøvde å melde deg av '.
                $course->getName().'. vennligst prøv igjen');
        }

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
     *     name="withdraw_from_course_participant"
     * )
     * @Method("POST")
     */
    public function withdrawParticipantAction(Participant $participant, Request $request)
    {
        if ($participant->getUser()->getId() == $this->getUser()->getId()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($participant);
            $manager->flush();

            $child = $participant->getChild();
            if ($child) {
                $this->addFlash('success', 'Du har meldt '.$child.' av '.$participant->getCourse()->getName());
            } else {
                $this->addFlash('success', 'Du har meldt deg av '.$participant->getCourse()->getName());
            }
        } else {
            $this->addFlash('danger', 'Det skjedde en feil da vi prøvde å melde deg av '.
                $participant->getCourse()->getName().' vennligst prøv igjen');
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
