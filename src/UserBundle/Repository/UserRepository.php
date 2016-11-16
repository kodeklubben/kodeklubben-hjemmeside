<?php

namespace UserBundle\Repository;

use AppBundle\Entity\Semester;
use Doctrine\ORM\EntityRepository;
use UserBundle\Entity\User;

class UserRepository extends EntityRepository
{
    /**
     * @param string $email
     *
     * @return User
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findUserByEmail($email)
    {
        return $this->createQueryBuilder('user')
            ->select('user')
            ->where('user.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param Semester $semester
     *
     * @return User[]
     */
    public function findNewUsersBySemester(Semester $semester)
    {
        return $this->createQueryBuilder('user')
            ->select('user')
            ->where('user.createdDatetime >= :semesterStart')
            ->andWhere('user.createdDatetime < :semesterEnd')
            ->setParameter('semesterStart', $semester->getStartTime())
            ->setParameter('semesterEnd', $semester->getEndTime())
            ->getQuery()
            ->getResult();
    }
}
