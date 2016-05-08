<?php


namespace AppBundle\Repository;

use AppBundle\Entity\Course;
use AppBundle\Entity\Semester;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 *
 */
class CourseRepository extends EntityRepository
{
    public function findAll(){
        return $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.deleted = false')
            ->orderBy('c.name')
            ->getQuery()
            ->getResult();
    }

    public function findByTutor(User $user){
        return $this->createQueryBuilder('c')
            ->select('c')
            ->join('c.tutors', 'tutors')
            ->where('c.deleted = false')
            ->andWhere('tutors = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function findByTutorAndCourse(User $user, Course $course){
        return $this->createQueryBuilder('c')
            ->select('c')
            ->join('c.tutors', 'tutors')
            ->where('c.deleted = false')
            ->andWhere('c = :course')
            ->andWhere('tutors = :user')
            ->setParameter('user', $user)
            ->setParameter('course', $course)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Semester $semester
     * @return Course[]
     */
    public function findCoursesBySemester(Semester $semester)
    {
        return $this->createQueryBuilder('course')
            ->select('course')
            ->where('course.semester = :semester')
            ->setParameter('semester', $semester)
            ->getQuery()
            ->getResult();
    }
}
