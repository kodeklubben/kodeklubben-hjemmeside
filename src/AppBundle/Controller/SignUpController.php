<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Course;
use AppBundle\Entity\Participant;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SignUpController extends Controller
{
    public function showAction()
    {
        $courses = $this->getDoctrine()->getRepository('AppBundle:Course')->findAll();
        $courseTypes = $this->getDoctrine()->getRepository('AppBundle:CourseType')->findAll();
        $user = $this->getUser();
        if ($this->get('security.authorization_checker')->isGranted('ROLE_PARTICIPANT'))
        {
            $participants = $this->getDoctrine()->getRepository('AppBundle:Participant')->findBy(array('user' => $user));
            return $this->render('sign_up/participant.html.twig', array(
                'courses' => $courses,
                'courseTypes' => $courseTypes,
                'user' => $user,
                'participants' => $participants
            ));
        }elseif($this->get('security.authorization_checker')->isGranted('ROLE_TUTOR'))
        {
            $tutoringCourses = $this->getDoctrine()->getRepository('AppBundle:Course')->findByTutor($user);
            return $this->render('sign_up/tutor.html.twig', array(
                'courses' => $courses,
                'courseTypes' => $courseTypes,
                'user' => $user,
                'tutoringCourses' => $tutoringCourses
            ));
        }else{
            // This should never happen
            return $this->createAccessDeniedException();
        }
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
            $participant = new Participant();
            $participant->setUser($user);
            $participant->setCourse($course);
            $participant->setFirstName($user->getFirstName());
            $participant->setLastName($user->getLastName());
            $participant->setIsChild(false);
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

    public function signOffAction(Course $course)
    {
        $user = $this->getUser();
        // Check if user is already signed up to the course
        $isAlreadyParticipant = count($this->getDoctrine()->getRepository('AppBundle:Participant')->findBy(array('course' => $course, 'user' => $user))) > 0;
        $isAlreadyTutor = count($this->getDoctrine()->getRepository('AppBundle:Course')->findByTutorAndCourse($user, $course)) > 0;
        if (!$isAlreadyParticipant and !$isAlreadyTutor) return $this->redirectToRoute('sign_up');

        // Sign up as a participant if the user is logged in as a participant user
        if ($this->get('security.authorization_checker')->isGranted('ROLE_PARTICIPANT')) {
            $participant = $this->getDoctrine()->getRepository('AppBundle:Participant')->findOneBy(array('course' => $course, 'user' => $user));
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($participant);
            $manager->flush();

            // Sign up as a tutor if the user is logged in as a tutor user
        } elseif ($this->get('security.authorization_checker')->isGranted('ROLE_TUTOR')) {
            $course->removeTutor($user);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($course);
            $manager->flush();
        }
        return $this->redirectToRoute('sign_up');
    }

}
