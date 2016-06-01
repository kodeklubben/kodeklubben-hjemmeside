<?php

namespace CodeClubBundle\DataFixtures\ORM;

use CodeClubBundle\Entity\Participant;
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
        $participant->setFirstName($participant->getUser()->getFirstName());
        $participant->setLastName($participant->getUser()->getLastName());
        $manager->persist($participant);

        $participant = new Participant();
        $participant->setCourse($this->getReference('course_java'));
        $participant->setUser($this->getReference('user-participant'));
        $participant->setFirstName($participant->getUser()->getFirstName());
        $participant->setLastName($participant->getUser()->getLastName());
        $manager->persist($participant);

        $manager->flush();

    }

    public function getOrder()
    {
        return 7;
    }
}