<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Club;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\StaticContent;

/**
 * This custom Doctrine repository is empty because so far we don't need any custom
 * method to query for application static content information. But it's always a good practice
 * to define a custom repository that will be used when the application grows.
 * See http://symfony.com/doc/current/book/doctrine.html#custom-repository-classes.
 */
class StaticContentRepository extends EntityRepository
{
    /**
     * @param string $stringId
     * @param Club   $club
     *
     * @return StaticContent
     */
    public function findOneByStringId($stringId, Club $club)
    {
        return $this->createQueryBuilder('sc')
            ->select('sc')
            ->where('sc.idString = :stringId')
            ->andWhere('sc.club = :club')
            ->setParameter('stringId', $stringId)
            ->setParameter('club', $club)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
