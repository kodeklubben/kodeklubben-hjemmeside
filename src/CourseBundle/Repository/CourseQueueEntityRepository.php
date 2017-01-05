<?php

namespace CourseBundle\Repository;

use AppBundle\Entity\Semester;
use CourseBundle\Entity\Course;
use CourseBundle\Entity\CourseQueueEntity;
use Doctrine\ORM\EntityRepository;
use UserBundle\Entity\Child;
use UserBundle\Entity\User;

class CourseQueueEntityRepository extends EntityRepository
{
    /**
     * @param Course $course
     *
     * @return CourseQueueEntity[]
     */
    public function findByCourse(Course $course)
    {
        return $this->createQueryBuilder('queue_entity')
            ->select('queue_entity')
            ->where('queue_entity.course = :course')
            ->setParameter('course', $course)
            ->getQuery()
            ->getResult();
    }
    /**
     * @param Course $course
     * @param Child  $child
     *
     * @return CourseQueueEntity | null
     */
    public function findByCourseAndChild(Course $course, Child $child)
    {
        return $this->createQueryBuilder('queue_entity')
            ->select('queue_entity')
            ->where('queue_entity.course = :course')
            ->andWhere('queue_entity.child = :child')
            ->setParameter('course', $course)
            ->setParameter('child', $child)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param User     $user
     * @param Semester $semester
     *
     * @return CourseQueueEntity[]
     */
    public function findByUserAndSemester(User $user, Semester $semester)
    {
        return $this->createQueryBuilder('queue_entity')
            ->select('queue_entity')
            ->join('queue_entity.course', 'course')
            ->join('course.courseType', 'courseType')
            ->where('queue_entity.user = :user')
            ->andWhere('course.semester = :semester')
            ->andWhere('course.deleted = false')
            ->andWhere('courseType.deleted = false')
            ->setParameter('user', $user)
            ->setParameter('semester', $semester)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User   $user
     * @param Course $course
     *
     * @return CourseQueueEntity|null
     */
    public function findOneByUserAndCourse(User $user, Course $course)
    {
        return $this->createQueryBuilder('queue_entity')
            ->select('queue_entity')
            ->where('queue_entity.user = :user')
            ->andWhere('queue_entity.course = :course')
            ->setParameter('user', $user)
            ->setParameter('course', $course)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
