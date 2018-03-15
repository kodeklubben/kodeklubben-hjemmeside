<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class.
 *
 * @ORM\Table(name="participant")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ParticipantRepository")
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
     * @var Child
     *
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Child", inversedBy="participants")
     * @ORM\JoinColumn(name="child_id", referencedColumnName="id", nullable=true)
     */
    private $child;

    /**
     * @var Course
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Course", inversedBy="participants")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     * @Assert\Valid
     */
    private $course;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\User", inversedBy="participants")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Assert\Valid
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * ParticipantBase constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
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
     * @return string
     */
    public function getFullName()
    {
        if ($this->child !== null) {
            return $this->child->getFullName();
        } else {
            return $this->user->getFullName();
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getFullName();
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
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }
}
