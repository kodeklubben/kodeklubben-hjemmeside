<?php

namespace CourseBundle\Controller;

use UserBundle\Entity\Child;
use CourseBundle\Entity\Course;
use CourseBundle\Entity\CourseType;
use UserBundle\Entity\Participant;
use UserBundle\Entity\Tutor;
use UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SignUpController extends Controller
{
    public function showAction()
    {
        $currentSemester = $this->getDoctrine()->getRepository('CodeClubBundle:Semester')->findCurrentSemester();
        $allCourseTypes = $this->getDoctrine()->getRepository('CourseBundle:CourseType')->findAll();
        $courseTypes = $this->filterActiveCourses($allCourseTypes);
        $user = $this->getUser();
        $parameters = array(
            'currentSemester' => $currentSemester,
            'courseTypes' => $courseTypes,
            'user' => $user
        );
        if ($this->get('security.authorization_checker')->isGranted('ROLE_PARENT')) {
            $participants = $this->getDoctrine()->getRepository('UserBundle:Participant')->findBy(array('user' => $user));
            $children = $this->getDoctrine()->getRepository('UserBundle:Child')->findBy(array('parent' => $user));
            return $this->render('@CodeClub/sign_up/parent.html.twig', array_merge($parameters, array(
                'participants' => $participants,
                'children' => $children
            )));
        } elseif ($this->get('security.authorization_checker')->isGranted('ROLE_PARTICIPANT')) {
            $participants = $this->getDoctrine()->getRepository('UserBundle:Participant')->findBy(array('user' => $user));
            return $this->render('@CodeClub/sign_up/participant.html.twig', array_merge($parameters, array(
                'participants' => $participants,
            )));
        } elseif ($this->get('security.authorization_checker')->isGranted('ROLE_TUTOR')) {
            $tutors = $this->getDoctrine()->getRepository('UserBundle:Tutor')->findBy(array('user' => $user));
            return $this->render('@CodeClub/sign_up/tutor.html.twig', array_merge($parameters,array(
                'tutors' => $tutors
            )));
        } else {
            // This should never happen
            return $this->createAccessDeniedException();
        }
    }

    public function signUpChildAction(Course $course, Child $child)
    {
        // Check if child is already signed up to the course or the course is set for another semester
        $isAlreadyParticipant = count($this->getDoctrine()->getRepository('UserBundle:Participant')->findBy(array('course' => $course, 'child' => $child))) > 0;
        $isThisSemester = $course->getSemester()->isEqualTo($this->getDoctrine()->getRepository('CodeClubBundle:Semester')->findCurrentSemester());
        if ($isAlreadyParticipant || !$isThisSemester) return $this->redirectToRoute('sign_up');
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

    public function signUpAction(Course $course, Request $request)
    {
        $user = $this->getUser();
        // Check if user is already signed up to the course or the course is set for another semester
        $isAlreadyParticipant = count($this->getDoctrine()->getRepository('UserBundle:Participant')->findBy(array('course' => $course, 'user' => $user))) > 0;
        $isAlreadyTutor = count($this->getDoctrine()->getRepository('UserBundle:Tutor')->findBy(array('course' => $course, 'user' => $user))) > 0;
        $isThisSemester = $course->getSemester()->isEqualTo($this->getDoctrine()->getRepository('CodeClubBundle:Semester')->findCurrentSemester());
        if ($isAlreadyParticipant || $isAlreadyTutor || !$isThisSemester) return $this->redirectToRoute('sign_up');

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
        }
        return $this->redirectToRoute('sign_up');
    }

    public function withdrawTutorAction(Request $request)
    {
        $tutorRepo = $this->getDoctrine()->getRepository('UserBundle:Tutor');
        $isAdmin = $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN');
        $tutorId = $request->get('tutorId');
        $course = $this->getDoctrine()->getRepository('CourseBundle:Course')->find($request->get('courseId'));
        $user = $this->getUser();
        if($isAdmin && !is_null($tutorId)){
            $tutor =  $tutorRepo->find($tutorId);
        }else{
            $tutor = $tutorRepo->findOneBy(array('user' => $user, 'course' => $course));
        }

        // Check if tutor is already signed up to the course
        $isAlreadyTutor = $tutor->getCourse()->getId() === $course->getId();
        if ($isAlreadyTutor) {
            $course->removeTutor($tutor);
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($tutor);
            $manager->persist($course);
            $manager->flush();
        }
        return $this->redirect($request->headers->get('referer'));
    }

    public function withdrawParticipantAction(Participant $participant, Request $request)
    {
        $isAdmin = $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN');
        if ($isAdmin || ($participant->getUser()->getId() == $this->getUser()->getId())) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($participant);
            $manager->flush();
        }
        return $this->redirect($request->headers->get('referer'));

    }

    /**
     * @param CourseType[] $allCourseTypes
     * @return array
     */
    private function filterActiveCourses($allCourseTypes)
    {
        $currentSemester = $this->getDoctrine()->getRepository('CodeClubBundle:Semester')->findCurrentSemester();
        $res = array();
        foreach ($allCourseTypes as $courseType)
        {
            foreach ($courseType->getCourses() as $course)
            {
                if($course->getSemester()->isEqualTo($currentSemester) && !$course->isDeleted())
                {
                    $courseTypeName = $courseType->getName();
                    if(!key_exists($courseTypeName,$res )) $res[$courseTypeName] = array();
                    $res[$courseTypeName][] = $course;
                }
            }
        }
        return $res;
    }

}
