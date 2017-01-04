<?php

namespace UserBundle\Repository;

use AppBundle\Entity\Semester;
use CodeClubBundle\Entity\Club;
use CourseBundle\Entity\Course;
use Doctrine\ORM\EntityRepository;
use UserBundle\Entity\Child;
use UserBundle\Entity\Participant;
use UserBundle\Entity\User;

class ParticipantRepository extends EntityRepository
{
    /**
     * @param User $user
     *
     * @return Participant[]
     */
    public function findByUserThisAndLaterSemesters(User $user)
    {
        $now = new \DateTime();
        $year = $now->format('Y');
        $isSpring = intval($now->format('m')) <= 7;
        $query = $this->createQueryBuilder('participant')
            ->select('participant')
            ->join('participant.course', 'course')
            ->join('course.semester', 'semester')
            ->where('course.deleted = false')
            ->andWhere('participant.user = :user')
            ->setParameter('user', $user)
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
     * @param User $user
     *
     * @return Participant[]
     */
    public function findByUser(User $user)
    {
        return $this->createQueryBuilder('participant')
            ->select('participant')
            ->where('participant.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Course $course
     * @param Child  $child
     *
     * @return Participant | null
     */
    public function findByCourseAndChild(Course $course, Child $child)
    {
        return $this->createQueryBuilder('participant')
            ->select('participant')
            ->where('participant.course = :course')
            ->andWhere('participant.child = :child')
            ->setParameter('course', $course)
            ->setParameter('child', $child)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param Semester $semester
     * @param Club     $club
     *
     * @return \UserBundle\Entity\Participant[]
     */
    public function findBySemester(Semester $semester, Club $club)
    {
        return $this->createQueryBuilder('participant')
            ->select('participant')
            ->join('participant.course', 'course')
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
     * @param Club $club
     *
     * @return Participant[]
     */
    public function findByClub(Club $club)
    {
        return $this->createQueryBuilder('participant')
            ->select('participant')
            ->join('participant.course', 'course')
            ->join('course.courseType', 'courseType')
            ->where('courseType.club = :club')
            ->andWhere('course.deleted = false')
            ->andWhere('courseType.deleted = false')
            ->setParameter('club', $club)
            ->getQuery()
            ->getResult();
    }
}
