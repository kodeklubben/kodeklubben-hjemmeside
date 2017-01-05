<?php

namespace CourseBundle\DataFixtures\ORM;

use CourseBundle\Entity\CourseQueueEntity;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCourseQueueEntityData extends AbstractFixture implements OrderedFixtureInterface
{
    private $em;

    public function load(ObjectManager $manager)
    {
        $this->em = $manager;

        $queueEntity = new CourseQueueEntity();
        $queueEntity->setCourse($this->getReference('course_scratch_tuesday'));
        $queueEntity->setUser($this->getReference('user-parent'));
        $queueEntity->setChild($this->getReference('child1'));
        $manager->persist($queueEntity);

        $manager->flush();
    }

    public function getOrder()
    {
        return 7;
    }
}
