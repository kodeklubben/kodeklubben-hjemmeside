<?php


namespace AppBundle\Repository;

use AppBundle\Entity\Semester;
use Doctrine\ORM\EntityRepository;


class SemesterRepository extends EntityRepository
{
    /**
     * @return Semester
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findCurrentSemester()
    {
        $now = new \DateTime();
        $year = $now->format('Y');
        $isSpring = intval($now->format('m')) <= 7;
        return $this->createQueryBuilder('semester')
            ->select('semester')
            ->where('semester.year = :year')
            ->andWhere('semester.isSpring = :isSpring')
            ->setParameter('year', $year)
            ->setParameter('isSpring', $isSpring)
            ->getQuery()
            ->getSingleResult();
    }
}
