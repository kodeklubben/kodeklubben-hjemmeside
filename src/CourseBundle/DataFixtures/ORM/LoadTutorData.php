<?php

namespace CourseBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use CourseBundle\Entity\Tutor;

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
        $tutor->setCourse($this->getReference('course_scratch_monday'));
        $tutor->setUser($this->getReference('user-tutor-0'));
        $tutor->setSubstitute(true);
        $manager->persist($tutor);

        $tutor = new Tutor();
        $tutor->setCourse($this->getReference('course_scratch_monday'));
        $tutor->setUser($this->getReference('user-tutor-1'));
        $tutor->setSubstitute(true);
        $manager->persist($tutor);

        $tutor = new Tutor();
        $tutor->setCourse($this->getReference('course_scratch_monday'));
        $tutor->setUser($this->getReference('user-tutor-2'));
        $tutor->setSubstitute(true);
        $manager->persist($tutor);

        $tutor = new Tutor();
        $tutor->setCourse($this->getReference('course_scratch_tuesday'));
        $tutor->setUser($this->getReference('user-tutor-0'));
        $tutor->setSubstitute(false);
        $manager->persist($tutor);

        $tutor = new Tutor();
        $tutor->setCourse($this->getReference('course_scratch_tuesday'));
        $tutor->setUser($this->getReference('user-tutor-1'));
        $tutor->setSubstitute(true);
        $manager->persist($tutor);

        $tutor = new Tutor();
        $tutor->setCourse($this->getReference('course_scratch_wednesday'));
        $tutor->setUser($this->getReference('user-tutor-2'));
        $tutor->setSubstitute(false);
        $manager->persist($tutor);

        $tutor = new Tutor();
        $tutor->setCourse($this->getReference('course_scratch_thursday'));
        $tutor->setUser($this->getReference('user-tutor-2'));
        $tutor->setSubstitute(true);
        $manager->persist($tutor);

        $tutor = new Tutor();
        $tutor->setCourse($this->getReference('course_scratch_tuesday'));
        $tutor->setUser($this->getReference('user-admin'));
        $tutor->setSubstitute(false);
        $manager->persist($tutor);

        $tutor = new Tutor();
        $tutor->setCourse($this->getReference('course_python_1_monday'));
        $tutor->setUser($this->getReference('user-tutor-0'));
        $tutor->setSubstitute(false);
        $manager->persist($tutor);

        $tutor = new Tutor();
        $tutor->setCourse($this->getReference('course_python_1_monday'));
        $tutor->setUser($this->getReference('user-tutor-1'));
        $tutor->setSubstitute(true);
        $manager->persist($tutor);

        $tutor = new Tutor();
        $tutor->setCourse($this->getReference('course_python_1_tuesday'));
        $tutor->setUser($this->getReference('user-tutor-1'));
        $tutor->setSubstitute(false);
        $manager->persist($tutor);

        $tutor = new Tutor();
        $tutor->setCourse($this->getReference('course_python_1_tuesday'));
        $tutor->setUser($this->getReference('user-tutor-2'));
        $tutor->setSubstitute(false);
        $manager->persist($tutor);

        $tutor = new Tutor();
        $tutor->setCourse($this->getReference('course_python_1_wednesday'));
        $tutor->setUser($this->getReference('user-tutor-3'));
        $tutor->setSubstitute(true);
        $manager->persist($tutor);

        $tutor = new Tutor();
        $tutor->setCourse($this->getReference('course_minecraft'));
        $tutor->setUser($this->getReference('user-tutor-5'));
        $tutor->setSubstitute(false);
        $manager->persist($tutor);

        $tutor = new Tutor();
        $tutor->setCourse($this->getReference('course_minecraft'));
        $tutor->setUser($this->getReference('user-tutor-6'));
        $tutor->setSubstitute(false);
        $manager->persist($tutor);

        $tutor = new Tutor();
        $tutor->setCourse($this->getReference('course_minecraft'));
        $tutor->setUser($this->getReference('user-tutor-7'));
        $tutor->setSubstitute(false);
        $manager->persist($tutor);

        $tutor = new Tutor();
        $tutor->setCourse($this->getReference('course_minecraft'));
        $tutor->setUser($this->getReference('user-tutor-8'));
        $tutor->setSubstitute(true);
        $manager->persist($tutor);

        $tutor = new Tutor();
        $tutor->setCourse($this->getReference('course_minecraft'));
        $tutor->setUser($this->getReference('user-tutor-9'));
        $tutor->setSubstitute(true);
        $manager->persist($tutor);

        $tutor = new Tutor();
        $tutor->setCourse($this->getReference('course_java'));
        $tutor->setUser($this->getReference('user-tutor'));
        $tutor->setSubstitute(true);
        $manager->persist($tutor);

        $tutor = new Tutor();
        $tutor->setCourse($this->getReference('course_java_past'));
        $tutor->setUser($this->getReference('user-tutor'));
        $tutor->setSubstitute(true);
        $manager->persist($tutor);

        $tutor = new Tutor();
        $tutor->setCourse($this->getReference('course_java_past'));
        $tutor->setUser($this->getReference('user-tutor-15'));
        $tutor->setSubstitute(true);
        $manager->persist($tutor);

        $tutor = new Tutor();
        $tutor->setCourse($this->getReference('course_java_past'));
        $tutor->setUser($this->getReference('user-tutor-16'));
        $tutor->setSubstitute(false);
        $manager->persist($tutor);

        $tutor = new Tutor();
        $tutor->setCourse($this->getReference('course_java_past'));
        $tutor->setUser($this->getReference('user-tutor-17'));
        $tutor->setSubstitute(false);
        $manager->persist($tutor);

        $tutor = new Tutor();
        $tutor->setCourse($this->getReference('course_java_past'));
        $tutor->setUser($this->getReference('user-tutor-18'));
        $tutor->setSubstitute(false);
        $manager->persist($tutor);

        $tutor = new Tutor();
        $tutor->setCourse($this->getReference('course_java_past'));
        $tutor->setUser($this->getReference('user-tutor-19'));
        $tutor->setSubstitute(false);
        $manager->persist($tutor);

        $manager->flush();
    }

    public function getOrder()
    {
        return 7;
    }
}
