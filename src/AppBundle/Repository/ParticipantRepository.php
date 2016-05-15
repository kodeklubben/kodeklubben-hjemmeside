<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Participant;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;


class ParticipantRepository extends EntityRepository
{
    /**
     * @param User $user
     * @return Participant[]
     */
    public function findByUserThisAndLaterSemesters(User $user)
    {
        $now = new \DateTime();
        $year = $now->format('Y');
        $isSpring = intval($now->format('m')) <= 7;
        $query = $this->createQueryBuilder('participant')
            ->select('participant')
            ->join('participant.course', 'course')
            ->join('course.semester', 'semester')
            ->where('course.deleted = false')
            ->andWhere('participant.user = :user')
            ->setParameter('user', $user)
            ->andWhere('semester.year >= :year')
            ->setParameter('year', $year);
        if(!$isSpring)
        {
            $query->andWhere('semester.isSpring = false');
        }
        return $query
            ->getQuery()
            ->getResult();
    }
}
