<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Child;
use AppBundle\Entity\Course;
use AppBundle\Entity\Participant;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SignUpController extends Controller
{
    public function showAction()
    {
        $courses = $this->getDoctrine()->getRepository('AppBundle:Course')->findAll();
        $courseTypes = $this->getDoctrine()->getRepository('AppBundle:CourseType')->findAll();
        $user = $this->getUser();
        if ($this->get('security.authorization_checker')->isGranted('ROLE_PARENT')) {
            $participants = $this->getDoctrine()->getRepository('AppBundle:Participant')->findBy(array('user' => $user));
            $children = $this->getDoctrine()->getRepository('AppBundle:Child')->findBy(array('parent' => $user));
            return $this->render('sign_up/parent.html.twig', array(
                'courses' => $courses,
                'courseTypes' => $courseTypes,
                'user' => $user,
                'participants' => $participants,
                'children' => $children
            ));
        } elseif ($this->get('security.authorization_checker')->isGranted('ROLE_PARTICIPANT')) {
            $participants = $this->getDoctrine()->getRepository('AppBundle:Participant')->findBy(array('user' => $user));
            return $this->render('sign_up/participant.html.twig', array(
                'courses' => $courses,
                'courseTypes' => $courseTypes,
                'user' => $user,
                'participants' => $participants
            ));
        } elseif ($this->get('security.authorization_checker')->isGranted('ROLE_TUTOR')) {
            $tutoringCourses = $this->getDoctrine()->getRepository('AppBundle:Course')->findByTutor($user);
            return $this->render('sign_up/tutor.html.twig', array(
                'courses' => $courses,
                'courseTypes' => $courseTypes,
                'user' => $user,
                'tutoringCourses' => $tutoringCourses
            ));
        } else {
            // This should never happen
            return $this->createAccessDeniedException();
        }
    }

    public function signUpChildAction(Course $course, Child $child)
    {
        // Check if child is already signed up to the course
        $isAlreadyParticipant = count($this->getDoctrine()->getRepository('AppBundle:Participant')->findBy(array('course' => $course, 'child' => $child))) > 0;
        if ($isAlreadyParticipant) return $this->redirectToRoute('sign_up');
        //Check if course is full
        if (count($course->getParticipants()) >= $course->getParticipantLimit()) return $this->redirectToRoute('sign_up');

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

        return $this->redirectToRoute('sign_up');
    }

    public function signUpAction(Course $course)
    {
        $user = $this->getUser();
        // Check if user is already signed up to the course
        $isAlreadyParticipant = count($this->getDoctrine()->getRepository('AppBundle:Participant')->findBy(array('course' => $course, 'user' => $user))) > 0;
        $isAlreadyTutor = count($this->getDoctrine()->getRepository('AppBundle:Course')->findByTutorAndCourse($user, $course)) > 0;
        if ($isAlreadyParticipant or $isAlreadyTutor) return $this->redirectToRoute('sign_up');

        // Sign up as a participant if the user is logged in as a participant user
        if ($this->get('security.authorization_checker')->isGranted('ROLE_PARTICIPANT')) {
            //Check if course is full
            if (count($course->getParticipants()) >= $course->getParticipantLimit()) return $this->redirectToRoute('sign_up');

            //Add user as participant to the course
            $participant = new Participant();
            $participant->setUser($user);
            $participant->setCourse($course);
            $participant->setFirstName($user->getFirstName());
            $participant->setLastName($user->getLastName());
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($participant);
            $manager->flush();

            // Sign up as a tutor if the user is logged in as a tutor user
        } elseif ($this->get('security.authorization_checker')->isGranted('ROLE_TUTOR')) {
            $course->addTutor($user);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($course);
            $manager->flush();
        }
        return $this->redirectToRoute('sign_up');
    }

    public function withdrawTutorAction(Request $request)
    {
        $userRepo = $this->getDoctrine()->getRepository('AppBundle:User');
        $isAdmin = $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN');
        $tutorId = $request->get('tutorId');
        $tutor = $isAdmin && !is_null($tutorId) ? $userRepo->find($tutorId) : $this->getUser();
        $course = $this->getDoctrine()->getRepository('AppBundle:Course')->find($request->get('courseId'));
        
        // Check if user is already signed up to the course
        $isAlreadyTutor = count($this->getDoctrine()->getRepository('AppBundle:Course')->findByTutorAndCourse($tutor, $course)) > 0;
        if ($isAlreadyTutor) {
            $course->removeTutor($tutor);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($course);
            $manager->flush();
        }
        return $this->redirect($request->headers->get('referer'));
    }

    public function withdrawParticipantAction(Request $request, Participant $participant)
    {
        $isAdmin = $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN');
        if ($isAdmin || ($participant->getUser()->getId() == $this->getUser()->getId())) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($participant);
            $manager->flush();
        }
        return $this->redirect($request->headers->get('referer'));

    }

}
