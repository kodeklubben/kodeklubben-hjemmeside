<?php


namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 *
 */
class CourseTypeRepository extends EntityRepository
{
    public function findAll(){
        return $this->createQueryBuilder('ct')
            ->select('ct')
            ->where('ct.deleted = false')
            ->orderBy('ct.name', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
