<?php

namespace UserBundle\DataFixtures\ORM;

use UserBundle\Entity\Participant;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadParticipantData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $participant = new Participant();
        $participant->setCourse($this->getReference('course_scratch_monday'));
        $participant->setUser($this->getReference('user-participant'));
        $manager->persist($participant);

        $participant = new Participant();
        $participant->setCourse($this->getReference('course_java'));
        $participant->setUser($this->getReference('user-participant'));
        $manager->persist($participant);

        $participant = new Participant();
        $participant->setCourse($this->getReference('course_java'));
        $participant->setUser($this->getReference('user-parent'));
        $participant->setChild($this->getReference('child1'));
        $manager->persist($participant);

        $participant = new Participant();
        $participant->setCourse($this->getReference('course_python_1_monday'));
        $participant->setUser($this->getReference('user-parent'));
        $participant->setChild($this->getReference('child2'));
        $manager->persist($participant);

        $manager->flush();
    }

    public function getOrder()
    {
        return 7;
    }
}
