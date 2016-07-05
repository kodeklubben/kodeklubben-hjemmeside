<?php

namespace CodeClubBundle\DataFixtures\ORM;

use CourseBundle\Entity\Course;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCourseData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $course_scratch_monday = new Course();
        $course_scratch_monday->setCourseType($this->getReference('courseType-scratch'));
        $course_scratch_monday->setDescription('Scratch Mandag R10');
        $course_scratch_monday->setName('Scratch');
        $course_scratch_monday->setParticipantLimit(25);
        $course_scratch_monday->setSemester($this->getReference('semester-1'));
        $course_scratch_monday->addTutor($this->getReference('user-tutor'));
        $manager->persist($course_scratch_monday);

        $course_scratch_tuesday = new Course();
        $course_scratch_tuesday->setCourseType($this->getReference('courseType-scratch'));
        $course_scratch_tuesday->setDescription('Scratch Tirsdag R90');
        $course_scratch_tuesday->setName('Scratch');
        $course_scratch_tuesday->setParticipantLimit(25);
        $course_scratch_tuesday->setSemester($this->getReference('semester-1'));
        $manager->persist($course_scratch_tuesday);

        $course_scratch_wednesday = new Course();
        $course_scratch_wednesday->setCourseType($this->getReference('courseType-scratch'));
        $course_scratch_wednesday->setDescription('Scratch Onsdag R10');
        $course_scratch_wednesday->setName('Scratch');
        $course_scratch_wednesday->setParticipantLimit(25);
        $course_scratch_wednesday->setSemester($this->getReference('semester-1'));
        $manager->persist($course_scratch_wednesday);

        $course_scratch_thursday = new Course();
        $course_scratch_thursday->setCourseType($this->getReference('courseType-scratch'));
        $course_scratch_thursday->setDescription('Scratch Mandag R90');
        $course_scratch_thursday->setName('Scratch');
        $course_scratch_thursday->setParticipantLimit(25);
        $course_scratch_thursday->setSemester($this->getReference('semester-1'));
        $manager->persist($course_scratch_thursday);

        $course_python_1_monday = new Course();
        $course_python_1_monday->setCourseType($this->getReference('courseType-python'));
        $course_python_1_monday->setDescription('Python 1 Mandag R90');
        $course_python_1_monday->setName('Python 1');
        $course_python_1_monday->setParticipantLimit(20);
        $course_python_1_monday->setSemester($this->getReference('semester-1'));
        $manager->persist($course_python_1_monday);

        $course_python_1_tuesday = new Course();
        $course_python_1_tuesday->setCourseType($this->getReference('courseType-python'));
        $course_python_1_tuesday->setDescription('Python 1 Tirsdag R92');
        $course_python_1_tuesday->setName('Python 1');
        $course_python_1_tuesday->setParticipantLimit(20);
        $course_python_1_tuesday->setSemester($this->getReference('semester-1'));
        $manager->persist($course_python_1_tuesday);

        $course_python_1_wednesday = new Course();
        $course_python_1_wednesday->setCourseType($this->getReference('courseType-python'));
        $course_python_1_wednesday->setDescription('Python 1 Onsdag R90');
        $course_python_1_wednesday->setName('Python 1');
        $course_python_1_wednesday->setParticipantLimit(20);
        $course_python_1_wednesday->setSemester($this->getReference('semester-1'));
        $manager->persist($course_python_1_wednesday);

        $course_python_1_thursday = new Course();
        $course_python_1_thursday->setCourseType($this->getReference('courseType-python'));
        $course_python_1_thursday->setDescription('Python 1 Torsdag R92');
        $course_python_1_thursday->setName('Python 1');
        $course_python_1_thursday->setParticipantLimit(20);
        $course_python_1_thursday->setSemester($this->getReference('semester-1'));
        $manager->persist($course_python_1_thursday);

        $course_python_2 = new Course();
        $course_python_2->setCourseType($this->getReference('courseType-python'));
        $course_python_2->setDescription('Python 2 Torsdag R93');
        $course_python_2->setName('Python 2');
        $course_python_2->setParticipantLimit(20);
        $course_python_2->setSemester($this->getReference('semester-1'));
        $manager->persist($course_python_2);

        $course_minecraft = new Course();
        $course_minecraft->setCourseType($this->getReference('courseType-minecraft'));
        $course_minecraft->setDescription('Minecraft Mandag R92');
        $course_minecraft->setName('Minecraft');
        $course_minecraft->setParticipantLimit(15);
        $course_minecraft->setSemester($this->getReference('semester-1'));
        $manager->persist($course_minecraft);

        $course_java = new Course();
        $course_java->setCourseType($this->getReference('courseType-java'));
        $course_java->setDescription('Java Onsdag R92');
        $course_java->setName('Java');
        $course_java->setParticipantLimit(12);
        $course_java->setSemester($this->getReference('semester-1'));
        $manager->persist($course_java);

        $manager->flush();

        $this->setReference('course_scratch_monday', $course_scratch_monday);
        $this->setReference('course_scratch_tuesday', $course_scratch_tuesday);
        $this->setReference('course_scratch_wednesday', $course_scratch_wednesday);
        $this->setReference('course_scratch_thursday', $course_scratch_thursday);
        $this->setReference('course_python_1_monday', $course_python_1_monday);
        $this->setReference('course_python_1_tuesday', $course_python_1_tuesday);
        $this->setReference('course_python_1_wednesday', $course_python_1_wednesday);
        $this->setReference('course_python_1_thursday', $course_python_1_thursday);
        $this->setReference('course_python_2', $course_python_2);
        $this->setReference('course_minecraft', $course_minecraft);
        $this->setReference('course_java', $course_java);

    }

    public function getOrder()
    {
        return 5;
    }
}