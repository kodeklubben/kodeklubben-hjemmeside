<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PasswordResetRepository")
 * @ORM\Table(name="password_reset")
 */
class PasswordReset
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     * @Assert\Valid
     */
    protected $user;

    /**
     * @var string
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $hashedResetCode;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     * @Assert\DateTime()
     */
    protected $resetTime;

    /**
     * PasswordReset constructor.
     */
    public function __construct()
    {
        $this->resetTime = new \DateTime();
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
     * Set hashedResetCode.
     *
     * @param string $hashedResetCode
     *
     * @return PasswordReset
     */
    public function setHashedResetCode($hashedResetCode)
    {
        $this->hashedResetCode = $hashedResetCode;

        return $this;
    }

    /**
     * Get hashedResetCode.
     *
     * @return string
     */
    public function getHashedResetCode()
    {
        return $this->hashedResetCode;
    }

    /**
     * Set resetTime.
     *
     * @param \DateTime $resetTime
     *
     * @return PasswordReset
     */
    public function setResetTime($resetTime)
    {
        $this->resetTime = $resetTime;

        return $this;
    }

    /**
     * Get resetTime.
     *
     * @return \DateTime
     */
    public function getResetTime()
    {
        return $this->resetTime;
    }

    /**
     * Set user.
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return PasswordReset
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
