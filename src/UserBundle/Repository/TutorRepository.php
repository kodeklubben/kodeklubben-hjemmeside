<?php

namespace UserBundle\Repository;

use CourseBundle\Entity\Course;
use Doctrine\ORM\EntityRepository;
use UserBundle\Entity\Tutor;
use UserBundle\Entity\User;

class TutorRepository extends EntityRepository
{
    /**
     * @param Course $course
     * @param User   $user
     *
     * @return Tutor[] | null
     */
    public function findByCourseAndUser(Course $course, User $user)
    {
        return $this->createQueryBuilder('tutor')
            ->select('tutor')
            ->where('tutor.course = :course')
            ->andWhere('tutor.user = :user')
            ->setParameter('course', $course)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
