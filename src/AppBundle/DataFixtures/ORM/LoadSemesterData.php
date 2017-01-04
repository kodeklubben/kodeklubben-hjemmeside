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
        $this->setReference('semester-1', $semester);

        $semester = new Semester();
        $semester->setSpring(intval($now->format('n')) > 7);
        $semester->setYear(intval($now->format('Y')));
        $manager->persist($semester);

        $semester = new Semester();
        $semester->setSpring(intval($now->format('n')) <= 7);
        $semester->setYear(intval($now->format('Y')) + 1);
        $manager->persist($semester);
        $this->setReference('semester-future', $semester);

        $semester = new Semester();
        $semester->setSpring(intval($now->format('n')) > 7);
        $semester->setYear(intval($now->format('Y')) + 1);
        $manager->persist($semester);

        $semester = new Semester();
        $semester->setSpring(intval($now->format('n')) <= 7);
        $semester->setYear(intval($now->format('Y')) + 2);
        $manager->persist($semester);

        $semester = new Semester();
        $semester->setSpring(intval($now->format('n')) > 7);
        $semester->setYear(intval($now->format('Y')) + 2);
        $manager->persist($semester);

        $semester = new Semester();
        $semester->setSpring(intval($now->format('n')) <= 7);
        $semester->setYear(intval($now->format('Y')) - 1);
        $manager->persist($semester);
        $this->setReference('semester-past', $semester);

        $semester = new Semester();
        $semester->setSpring(intval($now->format('n')) > 7);
        $semester->setYear(intval($now->format('Y')) - 1);
        $manager->persist($semester);

        $semester = new Semester();
        $semester->setSpring(intval($now->format('n')) <= 7);
        $semester->setYear(intval($now->format('Y')) - 2);
        $manager->persist($semester);

        $semester = new Semester();
        $semester->setSpring(intval($now->format('n')) > 7);
        $semester->setYear(intval($now->format('Y')) - 2);
        $manager->persist($semester);

        $manager->flush();
    }

    public function getOrder()
    {
        return 4;
    }
}
