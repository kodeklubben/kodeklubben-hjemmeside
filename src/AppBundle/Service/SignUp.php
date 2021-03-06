<?php

namespace AppBundle\Service;

use AppBundle\Entity\Course;
use AppBundle\Entity\Child;
use AppBundle\Entity\Participant;
use AppBundle\Entity\Tutor;
use AppBundle\Entity\User;

class SignUp
{
    public function createParticipant(Course $course, User $user, Child $child = null)
    {
        $participant = new Participant();
        $participant->setUser($user);
        $participant->setCourse($course);
        $participant->setChild($child);

        return $participant;
    }

    public function createTutor(Course $course, User $user, $isSubstitute)
    {
        $tutor = new Tutor();
        $tutor->setUser($user);
        $tutor->setCourse($course);
        $tutor->setSubstitute($isSubstitute);

        return $tutor;
    }
}
