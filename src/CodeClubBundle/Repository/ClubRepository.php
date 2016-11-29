<?php

namespace CodeClubBundle\Repository;

use CodeClubBundle\Entity\Club;
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
            ->where('club.subdomain = :subdomain')
            ->setParameter('subdomain', $subdomain)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
