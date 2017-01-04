<?php

namespace UserBundle\Repository;

use AppBundle\Entity\Semester;
use CodeClubBundle\Entity\Club;
use CourseBundle\Entity\Course;
use Doctrine\ORM\EntityRepository;
use UserBundle\Entity\Tutor;
use UserBundle\Entity\User;

class TutorRepository extends EntityRepository
{
    /**
     * @param User $user
     *
     * @return Tutor[]
     */
    public function findByUser(User $user)
    {
        return $this->createQueryBuilder('tutor')
            ->select('tutor')
            ->where('tutor.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Club $club
     *
     * @return Tutor[]
     */
    public function findByClub(Club $club)
    {
        return $this->createQueryBuilder('tutor')
            ->select('tutor')
            ->join('tutor.course', 'course')
            ->join('course.courseType', 'courseType')
            ->where('courseType.club = :club')
            ->andWhere('course.deleted = false')
            ->andWhere('courseType.deleted = false')
            ->setParameter('club', $club)
            ->getQuery()
            ->getResult();
    }

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
     * @param Club     $club
     *
     * @return \UserBundle\Entity\Tutor[]
     */
    public function findBySemester(Semester $semester, Club $club)
    {
        return $this->createQueryBuilder('tutor')
            ->select('tutor')
            ->join('tutor.course', 'course')
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

    /**
     * @param User $user
     *
     * @return Tutor[]
     */
    public function findByUserThisAndLaterSemesters(User $user)
    {
        $now = new \DateTime();
        $year = $now->format('Y');
        $isSpring = intval($now->format('m')) <= 7;
        $query = $this->createQueryBuilder('tutor')
            ->select('tutor')
            ->join('tutor.course', 'course')
            ->join('course.semester', 'semester')
            ->where('tutor.user = :user')
            ->andWhere('course.deleted = false')
            ->andWhere('semester.year >= :year')
            ->setParameter('user', $user)
            ->setParameter('year', $year);
        if (!$isSpring) {
            $query->andWhere('semester.isSpring = false');
        }

        return $query
            ->getQuery()
            ->getResult();
    }
}
