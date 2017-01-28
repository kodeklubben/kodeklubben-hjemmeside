<?php

namespace UserBundle\Repository;

use AppBundle\Entity\Semester;
use CodeClubBundle\Entity\Club;
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
     * @param Club     $club
     *
     * @return \UserBundle\Entity\User[]
     */
    public function findNewUsersBySemester(Semester $semester, Club $club)
    {
        return $this->createQueryBuilder('user')
            ->select('user')
            ->where('user.createdDatetime >= :semesterStart')
            ->andWhere('user.createdDatetime < :semesterEnd')
            ->andWhere('user.club = :club')
            ->setParameter('semesterStart', $semester->getStartTime())
            ->setParameter('semesterEnd', $semester->getEndTime())
            ->setParameter('club', $club)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param string $username
     * @param Club   $club
     *
     * @return User|null
     */
    public function findByUsernameAndClub(string $username, Club $club)
    {
        return $this->createQueryBuilder('user')
            ->select('user')
            ->where('lower(user.username) = lower(:username)')
            ->andWhere('user.club = :club')
            ->setParameter('username', $username)
            ->setParameter('club', $club)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param Club   $club
     * @param string $searchQuery
     *
     * @return \Doctrine\ORM\Query
     */
    public function findFilteredByClubQuery(Club $club, string $searchQuery)
    {
        return $this->createQueryBuilder('user')
            ->select('user')
            ->where('user.club = :club')
            ->andWhere("CONCAT(CONCAT(user.firstName, ' '), user.lastName) LIKE :searchQuery")
            ->orWhere('user.email LIKE :searchQuery')
            ->orWhere('user.phone LIKE :searchQuery')
            ->setParameter('searchQuery', "%$searchQuery%")
            ->setParameter('club', $club)
            ->getQuery();
    }

    /**
     * @param Club $club
     *
     * @return User[]
     */
    public function findByClub(Club $club)
    {
        return $this->createQueryBuilder('user')
            ->select('user')
            ->where('user.club = :club')
            ->setParameter('club', $club)
            ->getQuery()
            ->getResult();
    }
}
