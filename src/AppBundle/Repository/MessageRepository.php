<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Club;
use Doctrine\ORM\EntityRepository;

/**
 * MessageRepository.
 */
class MessageRepository extends EntityRepository
{
    /**
     * Find all messages that are not expired.
     *
     * @param Club $club
     *
     * @return \AppBundle\Entity\Message[]
     */
    public function findLatestMessages(Club $club)
    {
        return $this->createQueryBuilder('m')
            ->select('m')
            ->where('m.expireDate > ?1')
            ->andWhere('m.club = :club')
            ->setParameter(1, new \DateTime())
            ->setParameter('club', $club)
            ->orderBy('m.timestamp', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }
}
