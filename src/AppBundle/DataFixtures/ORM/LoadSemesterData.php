<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Message;
use AppBundle\Entity\Semester;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadSemesterData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $now = new \DateTime();
        $semester = new Semester();
        $semester->setIsSpring(intval($now->format('n')) <= 7);
        $semester->setYear($now->format('Y'));
        $manager->persist($semester);

        $manager->flush();
        $this->setReference('semester-1', $semester);
    }

    public function getOrder()
    {
        return 4;
    }
}