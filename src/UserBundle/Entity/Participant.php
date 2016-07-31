<?php

namespace UserBundle\Entity;

use UserBundle\Entity\User;
use CourseBundle\Entity\Course;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class
 *
 * @ORM\Table(name="participant")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\ParticipantRepository")
 * @UniqueEntity(
 *     fields={"user", "course"}
 * )
 */
class Participant
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
     * @var string
     *
     * @ORM\Column(name="first_name", type="string")
     * @Assert\NotBlank()
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string")
     * @Assert\NotBlank()
     */
    private $lastName;

    /**
     * @var Child
     * 
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\Child")
     * @ORM\JoinColumn(name="child_id", referencedColumnName="id", nullable=true)
     */
    private $child;

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
     * @ORM\ManyToOne(targetEntity="CourseBundle\Entity\Course", inversedBy="participants")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     * @Assert\Valid
     */
    private $course;

    /**
     * Get id
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
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return Child
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * @param Child $child
     */
    public function setChild($child)
    {
        $this->child = $child;
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
     * @return string
     */
    public function getFullName()
    {
        return $this->getFirstName() . " " . $this->getLastName();
    }
    

}

