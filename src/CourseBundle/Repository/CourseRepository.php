<?php

namespace CourseBundle\Repository;

use CodeClubBundle\Entity\Club;
use CourseBundle\Entity\Course;
use AppBundle\Entity\Semester;
use UserBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class CourseRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.deleted = false')
            ->orderBy('c.name')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Club $club
     *
     * @return Course[]
     */
    public function findByClub(Club $club)
    {
        return $this->createQueryBuilder('course')
            ->select('course')
            ->join('course.courseType', 'courseType')
            ->where('courseType.club = :club')
            ->andWhere('course.deleted = false')
            ->andWhere('courseType.deleted = false')
            ->setParameter('club', $club)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     *
     * @return Course[]
     */
    public function findByTutor(User $user)
    {
        return $this->createQueryBuilder('c')
            ->select('c')
            ->join('c.tutors', 'tutors')
            ->where('c.deleted = false')
            ->andWhere('tutors.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function findByTutorAndCourse(User $user, Course $course)
    {
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
     * @return Course[]
     */
    public function findByThisAndLaterSemesters()
    {
        $now = new \DateTime();
        $year = $now->format('Y');
        $isSpring = intval($now->format('m')) <= 7;
        $query = $this->createQueryBuilder('course')
            ->select('course')
            ->join('course.semester', 'semester')
            ->where('course.deleted = false')
            ->andWhere('semester.year >= :year')
            ->setParameter('year', $year);
        if (!$isSpring) {
            $query->andWhere('semester.isSpring = false');
        }

        return $query
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Semester $semester
     * @param Club     $club
     *
     * @return \CourseBundle\Entity\Course[]
     */
    public function findBySemester(Semester $semester, Club $club)
    {
        return $this->createQueryBuilder('course')
            ->select('course')
            ->join('course.courseType', 'courseType')
            ->where('course.semester = :semester')
            ->andWhere('course.deleted = false')
            ->andWhere('courseType.deleted = false')
            ->andWhere('courseType.club = :club')
            ->setParameter('semester', $semester)
            ->setParameter('club', $club)
            ->getQuery()
            ->getResult();
    }
}
