<?php

namespace UserBundle\Entity;

use CourseBundle\Entity\Course;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class.
 *
 * @ORM\Table(name="tutor")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\TutorRepository")
 * @UniqueEntity(
 *     fields={"user", "course"}
 * )
 */
class Tutor
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     * @Assert\Valid
     */
    private $user;

    /**
     * @var Course
     *
     * @ORM\ManyToOne(targetEntity="CourseBundle\Entity\Course", inversedBy="tutors")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id", onDelete="CASCADE")
     * @Assert\Valid
     */
    private $course;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $isSubstitute;

    /**
     * Tutor constructor.
     */
    public function __construct()
    {
        $this->isSubstitute = false;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->user->getFirstName();
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->user->getLastName();
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return Course
     */
    public function getCourse()
    {
        return $this->course;
    }

    /**
     * @param Course $course
     */
    public function setCourse($course)
    {
        $this->course = $course;
    }

    /**
     * @return bool
     */
    public function isSubstitute()
    {
        return $this->isSubstitute;
    }

    /**
     * @param bool $isSubstitute
     */
    public function setSubstitute($isSubstitute)
    {
        $this->isSubstitute = $isSubstitute;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->getFirstName().' '.$this->getLastName();
    }
}
