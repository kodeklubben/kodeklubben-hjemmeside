<?php

namespace UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use UserBundle\Entity\Tutor;

class LoadTutorData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $tutor = new Tutor();
        $tutor->setCourse($this->getReference('course_scratch_monday'));
        $tutor->setUser($this->getReference('user-tutor'));
        $tutor->setSubstitute(false);
        $manager->persist($tutor);

        $tutor = new Tutor();
        $tutor->setCourse($this->getReference('course_java'));
        $tutor->setUser($this->getReference('user-tutor'));
        $tutor->setSubstitute(true);
        $manager->persist($tutor);

        $tutor = new Tutor();
        $tutor->setCourse($this->getReference('course_scratch_tuesday'));
        $tutor->setUser($this->getReference('user-admin'));
        $tutor->setSubstitute(false);
        $manager->persist($tutor);

        $manager->flush();
    }

    public function getOrder()
    {
        return 7;
    }
}
