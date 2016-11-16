<?php

namespace CourseBundle\Repository;

use AppBundle\Entity\Semester;
use Doctrine\ORM\EntityRepository;

class CourseTypeRepository extends EntityRepository
{
    public function findAll()
    {
        return $this->createQueryBuilder('ct')
            ->select('ct')
            ->where('ct.deleted = false')
            ->orderBy('ct.name', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findNotHiddenBySemester(Semester $semester)
    {
        return $this->createQueryBuilder('ct')
            ->select('ct')
            ->join('ct.courses', 'courses')
            ->where('courses.semester = :semester')
            ->andWhere('courses.deleted = false')
            ->andWhere('ct.deleted = false')
            ->andWhere('ct.hideOnHomepage = false')
            ->setParameter('semester', $semester)
            ->orderBy('ct.name', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
