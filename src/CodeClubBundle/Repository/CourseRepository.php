<?php


namespace CodeClubBundle\Repository;

use CodeClubBundle\Entity\Course;
use CodeClubBundle\Entity\Semester;
use CodeClubBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

/**
 *
 */
class CourseRepository extends EntityRepository
{
    public function findAll(){
        return $this->createQueryBuilder('c')
            ->select('c')
            ->where('c.deleted = false')
            ->orderBy('c.name')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param User $user
     * @return Course[]
     */
    public function findByTutor(User $user){
        return $this->createQueryBuilder('c')
            ->select('c')
            ->join('c.tutors', 'tutors')
            ->where('c.deleted = false')
            ->andWhere('tutors = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

    public function findByTutorAndCourse(User $user, Course $course){
        return $this->createQueryBuilder('c')
            ->select('c')
            ->join('c.tutors', 'tutors')
            ->where('c.deleted = false')
            ->andWhere('c = :course')
            ->andWhere('tutors = :user')
            ->setParameter('user', $user)
            ->setParameter('course', $course)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Course[]
     */
    public function findByThisAndLaterSemesters()
    {
        $now = new \DateTime();
        $year = $now->format('Y');
        $isSpring = intval($now->format('m')) <= 7;
        $query = $this->createQueryBuilder('course')
            ->select('course')
            ->join('course.semester', 'semester')
            ->where('course.deleted = false')
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

    /**
     * @param User $tutor
     * @return Course[]
     */
    public function findByTutorThisAndLaterSemesters(User $tutor)
    {
        $now = new \DateTime();
        $year = $now->format('Y');
        $isSpring = intval($now->format('m')) <= 7;
        $query = $this->createQueryBuilder('course')
            ->select('course')
            ->join('course.semester', 'semester')
            ->join('course.tutors', 'tutors')
            ->where('tutors = :user')
            ->andWhere('course.deleted = false')
            ->setParameter('user', $tutor)
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

    public function findBySemester(Semester $semester)
    {
        return $this->createQueryBuilder('course')
            ->select('course')
            ->where('course.semester = :semester')
            ->andWhere('course.deleted = false')
            ->setParameter('semester', $semester)
            ->getQuery()
            ->getResult();
    }
}
