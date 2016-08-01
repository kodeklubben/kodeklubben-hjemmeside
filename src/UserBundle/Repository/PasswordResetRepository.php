<?php

namespace UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use UserBundle\Entity\PasswordReset;

/**
 * PasswordResetRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PasswordResetRepository extends EntityRepository
{

    public function findUserByResetcode($hashedResetCode){
        return $this->createQueryBuilder('PasswordReset')
            ->select('IDENTITY(PasswordReset.user)')
            ->where('PasswordReset.hashedResetCode = :hashedResetCode')
            ->setParameter('hashedResetCode', $hashedResetCode)
            ->getQuery()
            ->getSingleResult();
    }

    /**
     * @param string $hashedResetCode
     * @return PasswordReset
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findPasswordResetByHashedResetCode($hashedResetCode){
        return $this->createQueryBuilder('PasswordReset')
            ->select('PasswordReset')
            ->where('PasswordReset.hashedResetCode = :hashedResetCode')
            ->setParameter('hashedResetCode', $hashedResetCode)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function deletePasswordResetByHashedResetCode($hashedResetCode){
        return $this->createQueryBuilder('PasswordReset')
            ->delete()
            ->where('PasswordReset.hashedResetCode = :hashedResetCode')
            ->setParameter('hashedResetCode', $hashedResetCode)
            ->getQuery()
            ->getResult();
    }

    public function deletePasswordResetsByUser($user){
        return $this->createQueryBuilder('PasswordReset')
            ->delete()
            ->where('PasswordReset.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }
}