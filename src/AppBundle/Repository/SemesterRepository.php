<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Semester;
use Doctrine\ORM\EntityRepository;

class SemesterRepository extends EntityRepository
{
    public function findAll()
    {
        $now = new \DateTime();
        $year = intval($now->format('Y'));
        $isSpring = intval($now->format('m')) <= 7;
        $query = $this->createQueryBuilder('semester')
            ->select('semester');
        if ($isSpring) {
            $query
                ->where('semester.year <= :year');
        } else {
            $query
                ->where('semester.isSpring = :isSpring AND semester.year <= :year')
                ->orWhere('semester.isSpring = true AND semester.year <= :nextYear')
                ->setParameter('isSpring', $isSpring)
                ->setParameter('nextYear', $year + 1);
        }
        $query
            ->orderBy('semester.year', 'DESC')
            ->addOrderBy('semester.isSpring', 'ASC')
            ->setParameter('year', $year);

        return $query->getQuery()->getResult();
    }

    /**
     * @return Semester
     *
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

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function thisAndNextSemesterQuery()
    {
        $now = new \DateTime();
        $year = intval($now->format('Y'));
        $isSpring = intval($now->format('m')) <= 7;
        $query = $this->createQueryBuilder('semester')
            ->select('semester');
        if ($isSpring) {
            $query
                    ->where('semester.year = :year');
        } else {
            $query
                    ->where('semester.isSpring = :isSpring AND semester.year = :year')
                    ->orWhere('semester.isSpring = true AND semester.year = :nextYear')
                    ->setParameter('isSpring', $isSpring)
                    ->setParameter('nextYear', $year + 1);
        }
        $query
            ->orderBy('semester.year', 'ASC')
            ->addOrderBy('semester.isSpring', 'DESC')
            ->setParameter('year', $year);

        return $query;
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function allSemestersQuery()
    {
        $now = new \DateTime();
        $nextYear = intval($now->format('Y')) + 1;

        return $this->createQueryBuilder('semester')
            ->select('semester')
            ->where('semester.year <= :nextYear')
            ->setParameter('nextYear', $nextYear)
            ->orderBy('semester.isSpring', 'DESC')
            ->orderBy('semester.year', 'DESC');
    }
}
