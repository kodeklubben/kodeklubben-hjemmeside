<?php

namespace UserBundle\Entity;

use CourseBundle\Entity\CourseQueueEntity;
use CourseBundle\Entity\Participant;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="UserBundle\Repository\ChildRepository")
 * @ORM\Table(name="child")
 */
class Child
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstName", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $lastName;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="\UserBundle\Entity\User", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * @Assert\Valid
     */
    private $parent;

    /**
     * @var Participant[]
     *
     * @ORM\OneToMany(targetEntity="CourseBundle\Entity\Participant", mappedBy="child", cascade={"remove"})
     */
    private $participants;

    /**
     * @var CourseQueueEntity[]
     *
     * @ORM\OneToMany(targetEntity="CourseBundle\Entity\CourseQueueEntity", mappedBy="child", cascade={"remove"})
     */
    private $queues;

    /**
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
     * @return User
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param User $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->parent;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->firstName.' '.$this->lastName;
    }

    public function __toString()
    {
        return $this->getFirstName().' '.$this->getLastName();
    }

    /**
     * @return CourseQueueEntity[]
     */
    public function getQueues()
    {
        return $this->queues;
    }

    /**
     * @return \CourseBundle\Entity\Participant[]
     */
    public function getParticipants()
    {
        return $this->participants;
    }
}
