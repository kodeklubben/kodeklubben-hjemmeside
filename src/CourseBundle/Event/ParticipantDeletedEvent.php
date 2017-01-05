<?php

namespace CourseBundle\Event;

use CourseBundle\Entity\Participant;
use Symfony\Component\EventDispatcher\Event;

class ParticipantDeletedEvent extends Event
{
    const NAME = 'participant.deleted';

    private $participant;

    /**
     * ParticipantDeletedEvent constructor.
     *
     * @param Participant $participant
     */
    public function __construct(Participant $participant)
    {
        $this->participant = $participant;
    }

    /**
     * @return Participant
     */
    public function getParticipant()
    {
        return $this->participant;
    }
}
