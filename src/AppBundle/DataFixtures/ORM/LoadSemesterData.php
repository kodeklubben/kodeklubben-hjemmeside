<?php

namespace AppBundle\DataFixtures\ORM;

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
        $semester->setSpring(intval($now->format('n')) <= 7);
        $semester->setYear($now->format('Y'));
        $manager->persist($semester);

        $semester2 = new Semester();
        $semester2->setSpring(intval($now->format('n')) > 7);
        $semester2->setYear(intval($now->format('Y')));
        $manager->persist($semester2);

        $semester2 = new Semester();
        $semester2->setSpring(intval($now->format('n')) <= 7);
        $semester2->setYear(intval($now->format('Y')) + 1);
        $manager->persist($semester2);

        $semester2 = new Semester();
        $semester2->setSpring(intval($now->format('n')) > 7);
        $semester2->setYear(intval($now->format('Y')) + 1);
        $manager->persist($semester2);

        $semester2 = new Semester();
        $semester2->setSpring(intval($now->format('n')) <= 7);
        $semester2->setYear(intval($now->format('Y')) + 2);
        $manager->persist($semester2);

        $semester2 = new Semester();
        $semester2->setSpring(intval($now->format('n')) > 7);
        $semester2->setYear(intval($now->format('Y')) + 2);
        $manager->persist($semester2);

        $semester2 = new Semester();
        $semester2->setSpring(intval($now->format('n')) <= 7);
        $semester2->setYear(intval($now->format('Y')) - 1);
        $manager->persist($semester2);

        $semester2 = new Semester();
        $semester2->setSpring(intval($now->format('n')) > 7);
        $semester2->setYear(intval($now->format('Y')) - 1);
        $manager->persist($semester2);

        $semester2 = new Semester();
        $semester2->setSpring(intval($now->format('n')) <= 7);
        $semester2->setYear(intval($now->format('Y')) - 2);
        $manager->persist($semester2);

        $semester2 = new Semester();
        $semester2->setSpring(intval($now->format('n')) > 7);
        $semester2->setYear(intval($now->format('Y')) - 2);
        $manager->persist($semester2);

        $manager->flush();
        $this->setReference('semester-1', $semester);
    }

    public function getOrder()
    {
        return 4;
    }
}
