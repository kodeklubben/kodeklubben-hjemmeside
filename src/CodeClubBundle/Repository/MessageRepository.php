<?php

namespace CodeClubBundle\Repository;

use CodeClubBundle\Entity\Message;
use Doctrine\ORM\EntityRepository;

/**
 * MessageRepository.
 */
class MessageRepository extends EntityRepository
{
    /**
     * Find all messages that are not expired.
     *
     * @return Message[]
     */
    public function findLatestMessages()
    {
        return $this->createQueryBuilder('m')
            ->select('m')
            ->where('m.expireDate > ?1')
            ->setParameter(1, new \DateTime())
            ->orderBy('m.timestamp', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }
}
