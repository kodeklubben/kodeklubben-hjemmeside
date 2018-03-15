<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table(name="course_queue_entity")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CourseQueueEntityRepository")
 */
class CourseQueueEntity
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
     * @var Course
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Course", inversedBy="queue")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     * @Assert\Valid
     */
    private $course;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\User", inversedBy="queues")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Assert\Valid
     */
    private $user;

    /**
     * @var Child
     *
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Child", inversedBy="queues")
     * @ORM\JoinColumn(name="child_id", referencedColumnName="id", nullable=true)
     */
    private $child;

    /**
     * CourseQueueEntity constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getPosition()
    {
        $queue = $this->getCourse()->getQueue();

        $idx = array_search($this, $queue);

        if ($idx === false) {
            return -1;
        }

        return $idx + 1;
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
    public function setUser($user)
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
}
