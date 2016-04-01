<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\CourseClass;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCourseClassData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $lastMonday = (new \DateTime('last monday'))->setTime(18,15);
        $lastTuesday = (new \DateTime('last tuesday'))->setTime(18,15);
        $lastWednesday = (new \DateTime('last wednesday'))->setTime(18,15);
        $lastThursday = (new \DateTime('last thursday'))->setTime(18,15);
        $lastFriday = (new \DateTime('last friday'))->setTime(18,15);

        /*
         * Scratch Monday
         */
        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_scratch_monday'));
        $courseClass->setPlace('R10');
        $courseClass->setTime(clone $lastMonday);
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_scratch_monday'));
        $courseClass->setPlace('R10');
        $courseClass->setTime(clone ($lastMonday));
        $courseClass->getTime()->modify('-7day');
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_scratch_monday'));
        $courseClass->setPlace('R10');
        $courseClass->setTime(clone ($lastMonday));
        $courseClass->getTime()->modify('+7day');
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_scratch_monday'));
        $courseClass->setPlace('R10');
        $courseClass->setTime(clone ($lastMonday));
        $courseClass->getTime()->modify('+14day');
        $manager->persist($courseClass);

        /*
         * Scratch Tuesday
         */
        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_scratch_tuesday'));
        $courseClass->setPlace('R90');
        $courseClass->setTime(clone $lastTuesday);
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_scratch_tuesday'));
        $courseClass->setPlace('R90');
        $courseClass->setTime(clone ($lastTuesday));
        $courseClass->getTime()->modify('-7day');
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_scratch_tuesday'));
        $courseClass->setPlace('R90');
        $courseClass->setTime(clone ($lastTuesday));
        $courseClass->getTime()->modify('+7day');
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_scratch_tuesday'));
        $courseClass->setPlace('R90');
        $courseClass->setTime(clone ($lastTuesday));
        $courseClass->getTime()->modify('+14day');
        $manager->persist($courseClass);

        /*
         * Scratch Wednesday
         */
        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_scratch_wednesday'));
        $courseClass->setPlace('R10');
        $courseClass->setTime(clone $lastWednesday);
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_scratch_wednesday'));
        $courseClass->setPlace('R10');
        $courseClass->setTime(clone ($lastWednesday));
        $courseClass->getTime()->modify('-7day');
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_scratch_wednesday'));
        $courseClass->setPlace('R10');
        $courseClass->setTime(clone ($lastWednesday));
        $courseClass->getTime()->modify('+7day');
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_scratch_wednesday'));
        $courseClass->setPlace('R10');
        $courseClass->setTime(clone ($lastWednesday));
        $courseClass->getTime()->modify('+14day');
        $manager->persist($courseClass);

        /*
         * Scratch Thursday
         */
        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_scratch_thursday'));
        $courseClass->setPlace('R90');
        $courseClass->setTime(clone $lastThursday);
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_scratch_thursday'));
        $courseClass->setPlace('R90');
        $courseClass->setTime(clone ($lastThursday));
        $courseClass->getTime()->modify('-7day');
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_scratch_thursday'));
        $courseClass->setPlace('R90');
        $courseClass->setTime(clone ($lastThursday));
        $courseClass->getTime()->modify('+7day');
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_scratch_thursday'));
        $courseClass->setPlace('R90');
        $courseClass->setTime(clone ($lastThursday));
        $courseClass->getTime()->modify('+14day');
        $manager->persist($courseClass);

        /*
         * Python 1 Monday
         */
        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_python_1_monday'));
        $courseClass->setPlace('R90');
        $courseClass->setTime(clone $lastMonday);
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_python_1_monday'));
        $courseClass->setPlace('R90');
        $courseClass->setTime(clone ($lastMonday));
        $courseClass->getTime()->modify('-7day');
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_python_1_monday'));
        $courseClass->setPlace('R90');
        $courseClass->setTime(clone ($lastMonday));
        $courseClass->getTime()->modify('+7day');
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_python_1_monday'));
        $courseClass->setPlace('R90');
        $courseClass->setTime(clone ($lastMonday));
        $courseClass->getTime()->modify('+14day');
        $manager->persist($courseClass);

        /*
         * Python 1 Tuesday
         */
        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_python_1_tuesday'));
        $courseClass->setPlace('R92');
        $courseClass->setTime(clone $lastTuesday);
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_python_1_tuesday'));
        $courseClass->setPlace('R92');
        $courseClass->setTime(clone ($lastTuesday));
        $courseClass->getTime()->modify('-7day');
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_python_1_tuesday'));
        $courseClass->setPlace('R92');
        $courseClass->setTime(clone ($lastTuesday));
        $courseClass->getTime()->modify('+7day');
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_python_1_tuesday'));
        $courseClass->setPlace('R92');
        $courseClass->setTime(clone ($lastTuesday));
        $courseClass->getTime()->modify('+14day');
        $manager->persist($courseClass);

        /*
         * Python 1 Wednesday
         */
        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_python_1_wednesday'));
        $courseClass->setPlace('R90');
        $courseClass->setTime(clone $lastWednesday);
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_python_1_wednesday'));
        $courseClass->setPlace('R90');
        $courseClass->setTime(clone ($lastWednesday));
        $courseClass->getTime()->modify('-7day');
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_python_1_wednesday'));
        $courseClass->setPlace('R90');
        $courseClass->setTime(clone ($lastWednesday));
        $courseClass->getTime()->modify('+7day');
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_python_1_wednesday'));
        $courseClass->setPlace('R90');
        $courseClass->setTime(clone ($lastWednesday));
        $courseClass->getTime()->modify('+14day');
        $manager->persist($courseClass);

        /*
         * Python 1 Thursday
         */
        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_python_1_thursday'));
        $courseClass->setPlace('R92');
        $courseClass->setTime(clone $lastThursday);
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_python_1_thursday'));
        $courseClass->setPlace('R92');
        $courseClass->setTime(clone ($lastThursday));
        $courseClass->getTime()->modify('-7day');
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_python_1_thursday'));
        $courseClass->setPlace('R92');
        $courseClass->setTime(clone ($lastThursday));
        $courseClass->getTime()->modify('+7day');
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_python_1_thursday'));
        $courseClass->setPlace('R92');
        $courseClass->setTime(clone ($lastThursday));
        $courseClass->getTime()->modify('+14day');
        $manager->persist($courseClass);

        /*
         * Python 2 Thursday
         */
        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_python_2'));
        $courseClass->setPlace('R93');
        $courseClass->setTime(clone $lastThursday);
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_python_2'));
        $courseClass->setPlace('R93');
        $courseClass->setTime(clone ($lastThursday));
        $courseClass->getTime()->modify('-7day');
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_python_2'));
        $courseClass->setPlace('R93');
        $courseClass->setTime(clone ($lastThursday));
        $courseClass->getTime()->modify('+7day');
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_python_2'));
        $courseClass->setPlace('R93');
        $courseClass->setTime(clone ($lastThursday));
        $courseClass->getTime()->modify('+14day');
        $manager->persist($courseClass);

        /*
         * Java
         */
        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_java'));
        $courseClass->setPlace('R92');
        $courseClass->setTime(clone $lastWednesday);
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_java'));
        $courseClass->setPlace('R92');
        $courseClass->setTime(clone ($lastWednesday));
        $courseClass->getTime()->modify('-7day');
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_java'));
        $courseClass->setPlace('R92');
        $courseClass->setTime(clone ($lastWednesday));
        $courseClass->getTime()->modify('+7day');
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_java'));
        $courseClass->setPlace('R92');
        $courseClass->setTime(clone ($lastWednesday));
        $courseClass->getTime()->modify('+14day');
        $manager->persist($courseClass);

        /*
         * Python 2 Thursday
         */
        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_minecraft'));
        $courseClass->setPlace('R92');
        $courseClass->setTime(clone $lastMonday);
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_minecraft'));
        $courseClass->setPlace('R92');
        $courseClass->setTime(clone ($lastMonday));
        $courseClass->getTime()->modify('-7day');
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_minecraft'));
        $courseClass->setPlace('R92');
        $courseClass->setTime(clone ($lastMonday));
        $courseClass->getTime()->modify('+7day');
        $manager->persist($courseClass);

        $courseClass = new CourseClass();
        $courseClass->setCourse($this->getReference('course_minecraft'));
        $courseClass->setPlace('R92');
        $courseClass->setTime(clone ($lastMonday));
        $courseClass->getTime()->modify('+14day');
        $manager->persist($courseClass);


        $manager->flush();

    }

    public function getOrder()
    {
        return 6;
    }
}