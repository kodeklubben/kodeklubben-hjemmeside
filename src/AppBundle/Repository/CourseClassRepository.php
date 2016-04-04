<?php

namespace AppBundle\Repository;

use AppBundle\Entity\CourseClass;
use Doctrine\ORM\EntityRepository;

/**
 *
 */
class CourseClassRepository extends EntityRepository
{
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
     * @param int $week
     * @return CourseClass[]
     */
    public function findByWeek($week)
    {
        $now = new \DateTime();
        $currentYear = $now->format('Y');
        list($startOfWeek, $endOfWeek) = $this->_getStartAndEndDateOfWeek($week, $currentYear);
        return $this->createQueryBuilder('class')
            ->select('class')
            ->join('class.course', 'course')
            ->join('course.semester', 'semester')
            ->where('course.deleted = false')
            ->andWhere('class.time > :startOfWeek')
            ->andWhere('class.time < :endOfWeek')
            ->setParameter('startOfWeek', $startOfWeek)
            ->setParameter('endOfWeek', $endOfWeek)
            ->getQuery()
            ->getResult();
    }

    private function _getStartAndEndDateOfWeek($week, $year)
    {

        $time = strtotime("1 January $year", time());
        $day = date('w', $time);
        $time += ((7*$week)+1-$day)*24*3600;
        $return[0] = new \DateTime(date('Y-m-d 00:00:00', $time));
        $time += 6*24*3600;
        $return[1] = new \DateTime(date('Y-m-d 23:59:59', $time));
        return $return;
    }
}