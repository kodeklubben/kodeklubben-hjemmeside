<?php

namespace UserBundle\Repository;

use AppBundle\Entity\Semester;
use CourseBundle\Entity\Course;
use Doctrine\ORM\EntityRepository;
use UserBundle\Entity\Tutor;
use UserBundle\Entity\User;

class TutorRepository extends EntityRepository
{
    /**
     * @param Course $course
     * @param User   $user
     *
     * @return Tutor[] | null
     */
    public function findByCourseAndUser(Course $course, User $user)
    {
        return $this->createQueryBuilder('tutor')
            ->select('tutor')
            ->where('tutor.course = :course')
            ->andWhere('tutor.user = :user')
            ->setParameter('course', $course)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param Semester $semester
     *
     * @return Tutor[]
     */
    public function findBySemester(Semester $semester)
    {
        return $this->createQueryBuilder('tutor')
            ->select('tutor')
            ->join('tutor.course', 'course')
            ->where('course.semester = :semester')
            ->setParameter('semester', $semester)
            ->getQuery()
            ->getResult();
    }
}
