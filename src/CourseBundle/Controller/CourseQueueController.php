<?php

namespace CourseBundle\Controller;

use CourseBundle\Entity\Course;
use CourseBundle\Entity\CourseQueueEntity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CourseQueueController extends Controller
{
    /**
     * @param Course $course
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/kontrollpanel/kurs/venteliste/{id}", name="cp_course_queue")
     * @Method("GET")
     */
    public function showAction(Course $course)
    {
        $queueEntities = $this->getDoctrine()->getRepository('CourseBundle:CourseQueueEntity')->findByCourse($course);

        return $this->render('@Course/course_queue/show_course_queue.html.twig', array(
            'course' => $course,
            'queueEntities' => $queueEntities,
        ));
    }

    /**
     * @param Request $request
     * @param Course  $course
     *
     * @Route("/pamelding/venteliste/{id}", name="course_enqueue")
     * @Method("POST")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function enqueueAction(Request $request, Course $course)
    {
        $queueEntity = new CourseQueueEntity();

        if ($this->isGranted('ROLE_PARENT')) {
            $childId = $request->get('child');

            if (null === $childId || null === $child = $this->getDoctrine()->getRepository('UserBundle:Child')->find($childId)) {
                throw new BadRequestHttpException();
            }
            $alreadyInQueue = null !== $this->getDoctrine()->getRepository('CourseBundle:CourseQueueEntity')->findByCourseAndChild($course, $child);
            $alreadyInCourse = null !== $this->getDoctrine()->getRepository('CourseBundle:Participant')->findByCourseAndChild($course, $child);

            $queueEntity->setChild($child);
        } else {
            $alreadyInQueue = null !== $this->getDoctrine()->getRepository('CourseBundle:CourseQueueEntity')->findOneByUserAndCourse($this->getUser(), $course);
            $alreadyInCourse = null !== $this->getDoctrine()->getRepository('CourseBundle:Participant')->findOneByUserAndCourse($this->getUser(), $course);
        }

        $queueEntity->setUser($this->getUser());

        $queueEntity->setCourse($course);

        $name = $this->isGranted('ROLE_PARENT') ? $queueEntity->__toString() : 'Du';

        $validator = $this->get('validator');
        $errors = $validator->validate($queueEntity);

        if ($alreadyInCourse) {
            $this->addFlash('warning', "{$name} er allerede påmeldt {$course}: {$course->getDescription()}.");
        } elseif ($alreadyInQueue) {
            $this->addFlash('warning', "{$name} er allerede på ventelisten til {$course}: {$course->getDescription()}.");
        } elseif (count($errors) > 0) {
            $this->addFlash('danger', 'Det har oppstått en feil. Ingen handling utført.');
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->persist($queueEntity);
            $em->flush();

            $this->addFlash('success', "{$name} har blitt lagt til i ventelisten til {$course}: {$course->getDescription()}.");
        }

        return $this->redirectToRoute('sign_up');
    }

    /**
     * @param Request           $request
     * @param CourseQueueEntity $queueEntity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("pamelding/venteliste/meldav/{id}", name="queue_entity_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, CourseQueueEntity $queueEntity)
    {
        if ($queueEntity->getUser() !== $this->getUser()) {
            throw new BadRequestHttpException();
        }

        $child = $queueEntity->getChild();

        $em = $this->getDoctrine()->getManager();
        $em->remove($queueEntity);
        $em->flush();

        if ($child !== null) {
            $this->addFlash('success', "{$child} ble meldt av ventelisten til {$queueEntity->getCourse()}");
        } else {
            $this->addFlash('success', "Du ble meldt av ventelisten til {$queueEntity->getCourse()}");
        }

        return $this->redirect($request->headers->get('referer'));
    }
}
