<?php


namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 */
class CourseRepository extends EntityRepository
{
    public function findAll(){
        return $this->createQueryBuilder('cs')
            ->select('cs')
            ->where('cs.deleted = false')
            ->orderBy('cs.name')
            ->getQuery()
            ->getResult();
    }
}
