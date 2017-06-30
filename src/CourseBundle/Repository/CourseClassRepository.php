<?php

namespace CourseBundle\Repository;

use AppBundle\Entity\Semester;
use CodeClubBundle\Entity\Club;
use CourseBundle\Entity\CourseClass;
use Doctrine\ORM\EntityRepository;

class CourseClassRepository extends EntityRepository
{
    /**
     * @return CourseClass[]
     */
    public function findAll()
    {
        return $this->createQueryBuilder('class')
            ->orderBy('class.time', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return CourseClass[]
     */
    public function findCurrentClasses()
    {
        $now = new \DateTime();
        $currentYear = $now->format('Y');
        $isSpring = intval($now->format('n')) <= 7;

        return $this->createQueryBuilder('class')
            ->select('class')
            ->join('class.course', 'course')
            ->join('course.semester', 'semester')
            ->where('semester.year = :year')
            ->andWhere('semester.isSpring = :isSpring')
            ->andWhere('course.deleted = false')
            ->setParameter('year', $currentYear)
            ->setParameter('isSpring', $isSpring)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int      $week
     * @param Semester $semester
     * @param Club     $club
     *
     * @return \CourseBundle\Entity\CourseClass[]
     */
    public function findByWeek($week, Semester $semester, Club $club)
    {
        $now = new \DateTime();

        $currentYear = $now->format('Y');
        $startOfWeek = $this->getStartOfWeek($week, $currentYear);
        $endOfWeek = $this->getEndDateOfWeek($week, $currentYear);

        return $this->createQueryBuilder('class')
            ->select('class')
            ->join('class.course', 'course')
            ->join('course.courseType', 'courseType')
            ->where('course.deleted = false')
            ->andWhere('courseType.deleted = false')
            ->andWhere('class.time > :startOfWeek')
            ->andWhere('class.time < :endOfWeek')
            ->andWhere('course.semester = :semester')
            ->andWhere('courseType.club = :club')
            ->setParameter('startOfWeek', $startOfWeek)
            ->setParameter('endOfWeek', $endOfWeek)
            ->setParameter('semester', $semester)
            ->setParameter('club', $club)
            ->orderBy('class.time', 'ASC')
            ->getQuery()
            ->getResult();
    }

    private function getStartOfWeek($week, $year)
    {
        $start = new \DateTime();
        $start->setISODate($year, $week);
        $start->setTime(0, 0);

        return $start;
    }

    private function getEndDateOfWeek($week, $year)
    {
        $end = new \DateTime();
        $end->setISODate($year, $week, 7);
        $end->setTime(23, 59);

        return $end;
    }
}
