<?php

namespace UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\Participant;

class LoadParticipantData extends AbstractFixture implements OrderedFixtureInterface
{
    private $manager;

    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $participant = new Participant();
        $participant->setCourse($this->getReference('course_scratch_monday'));
        $participant->setUser($this->getReference('user-participant'));
        $manager->persist($participant);

        $participant = new Participant();
        $participant->setCourse($this->getReference('course_java'));
        $participant->setUser($this->getReference('user-participant'));
        $manager->persist($participant);

        $participant = new Participant();
        $participant->setCourse($this->getReference('course_python_1_monday'));
        $participant->setUser($this->getReference('user-parent'));
        $participant->setChild($this->getReference('child2'));
        $manager->persist($participant);

        $this->createParticipants(0, 15, 'course_scratch_monday');
        $this->createParticipants(0, 17, 'course_scratch_tuesday');
        $this->createParticipants(10, 18, 'course_scratch_wednesday');
        $this->createParticipants(5, 8, 'course_scratch_thursday');
        $this->createParticipants(0, 10, 'course_python_1_monday');
        $this->createParticipants(10, 21, 'course_python_1_tuesday');
        $this->createParticipants(15, 25, 'course_python_1_wednesday');
        $this->createParticipants(0, 2, 'course_python_1_thursday');
        $this->createParticipants(13, 23, 'course_minecraft');
        $this->createParticipants(14, 16, 'course_java');
        $this->createParticipants(0, 30, 'course_java_past');

        $manager->flush();
    }

    public function getOrder()
    {
        return 7;
    }

    private function createParticipants(int $start, int $end, $course)
    {
        for ($i = $start; $i < $end; ++$i) {
            $participant = new Participant();
            $participant->setCourse($this->getReference($course));
            $participant->setUser($this->getReference('user-parent-'.intdiv($i, 2)));
            $participant->setChild($this->getReference('child-'.$i));
            $this->manager->persist($participant);

            if ($i < ($start + intdiv(($end - $start), 2))) {
                $participant = new Participant();
                $participant->setCourse($this->getReference($course));
                $participant->setUser($this->getReference('user-participant-'.$i));
                $this->manager->persist($participant);
            }
        }
    }
}
