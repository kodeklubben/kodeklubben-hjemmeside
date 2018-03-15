<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Child;
use AppBundle\Entity\User;

/**
 * Child repository.
 */
class ChildRepository extends EntityRepository
{
    /**
     * @param User $user
     *
     * @return Child[]
     */
    public function findByParent(User $user)
    {
        return $this->createQueryBuilder('child')
            ->select('child')
            ->where('child.parent = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}
