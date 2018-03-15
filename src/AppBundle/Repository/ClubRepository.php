<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Club;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;

/**
 * ClubRepository.
 */
class ClubRepository extends EntityRepository
{
    /**
     * @param $subdomain
     *
     * @return Club
     *
     * @throws NonUniqueResultException
     */
    public function findOneBySubdomain($subdomain)
    {
        return $this->createQueryBuilder('club')
            ->select('club')
            ->where('lower(club.subdomain) = lower(:subdomain)')
            ->setParameter('subdomain', $subdomain)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return Club[]
     */
    public function findAll()
    {
        return $this->createQueryBuilder('club')
            ->select('club')
            ->where('club.subdomain != :default')
            ->setParameter('default', 'default')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Club[]
     */
    public function findAllSorted()
    {
        return $this->createQueryBuilder('club')
            ->select('club')
            ->where('club.subdomain != :default')
            ->setParameter('default', 'default')
            ->orderBy('club.name')
            ->getQuery()
            ->getResult();
    }
}
