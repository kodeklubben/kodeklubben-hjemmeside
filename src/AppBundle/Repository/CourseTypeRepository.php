<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Semester;
use AppBundle\Entity\Club;
use AppBundle\Entity\CourseType;
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

    /**
     * @param Club $club
     *
     * @return CourseType[]
     */
    public function findAllByClub(Club $club)
    {
        return $this->createQueryBuilder('ct')
            ->select('ct')
            ->where('ct.deleted = false')
            ->andWhere('ct.club = :club')
            ->setParameter('club', $club)
            ->orderBy('ct.name', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param Semester $semester
     * @param Club     $club
     *
     * @return CourseType[]
     */
    public function findNotHiddenBySemester(Semester $semester, Club $club)
    {
        return $this->createQueryBuilder('ct')
            ->select('ct')
            ->join('ct.courses', 'courses')
            ->where('courses.semester = :semester')
            ->andWhere('courses.deleted = false')
            ->andWhere('ct.deleted = false')
            ->andWhere('ct.hideOnHomepage = false')
            ->andWhere('ct.club = :club')
            ->setParameter('semester', $semester)
            ->setParameter('club', $club)
            ->orderBy('ct.name', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
