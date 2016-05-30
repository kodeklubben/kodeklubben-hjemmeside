<?php


namespace StaticContentBundle\Repository;

use Doctrine\ORM\EntityRepository;
use StaticContentBundle\Entity\StaticContent;

/**
 * This custom Doctrine repository is empty because so far we don't need any custom
 * method to query for application static content information. But it's always a good practice
 * to define a custom repository that will be used when the application grows.
 * See http://symfony.com/doc/current/book/doctrine.html#custom-repository-classes
 *
 */
class StaticContentRepository extends EntityRepository
{
    /**
     * @param string $stringId
     * @return StaticContent
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findOneByStringId($stringId)
    {
        return $this->createQueryBuilder('sc')
            ->select('sc')
            ->where('sc.idString = :stringId')
            ->setParameter('stringId', $stringId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
