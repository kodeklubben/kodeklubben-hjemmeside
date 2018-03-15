<?php

namespace AppBundle\Service;

use AppBundle\Entity\Course;
use AppBundle\Entity\Participant;
use Doctrine\ORM\EntityManager;

class CourseQueueManager
{
    private $em;
    private $notificationManager;

    /**
     * CourseQueueManager constructor.
     *
     * @param EntityManager       $em
     * @param NotificationManager $notificationManager
     */
    public function __construct(EntityManager $em, NotificationManager $notificationManager)
    {
        $this->em = $em;
        $this->notificationManager = $notificationManager;
    }

    /**
     * @param Course $course
     */
    public function promoteParticipantsFromQueueToCourse(Course $course)
    {
        $queue = $course->getQueue();

        while (!empty($queue) && !$course->isFull()) {
            $firstInQueue = array_shift($queue);

            $participant = new Participant();
            $participant->setChild($firstInQueue->getChild());
            $participant->setUser($firstInQueue->getUser());
            $participant->setCourse($course);

            $course->addParticipant($participant);

            $this->em->persist($participant);
            $this->em->remove($firstInQueue);

            $this->notificationManager->sendDequeueNotification($participant);
        }

        $this->em->flush();
    }
}
