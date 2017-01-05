<?php

namespace CourseBundle\EventSubscriber;

use CourseBundle\Event\ParticipantDeletedEvent;
use CourseBundle\Service\CourseQueueManager;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CourseQueueSubscriber implements EventSubscriberInterface
{
    private $logger;
    private $queueManager;

    /**
     * CourseQueueSubscriber constructor.
     *
     * @param CourseQueueManager $queueManager
     * @param Logger             $logger
     */
    public function __construct(CourseQueueManager $queueManager, Logger $logger)
    {
        $this->logger = $logger;
        $this->queueManager = $queueManager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            ParticipantDeletedEvent::NAME => array(
                array('logEvent', 1),
                array('dequeueEntities', 0),
            ),
        );
    }

    public function logEvent(ParticipantDeletedEvent $event)
    {
        $participant = $event->getParticipant();

        $this->logger->info("APP: Participant {$participant} deleted from course {$participant->getCourse()}.");
    }

    public function dequeueEntities(ParticipantDeletedEvent $event)
    {
        $this->queueManager->promoteParticipantsFromQueueToCourse($event->getParticipant()->getCourse());
    }
}
