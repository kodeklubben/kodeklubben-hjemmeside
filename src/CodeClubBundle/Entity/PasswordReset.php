<?php


namespace CodeClubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="CodeClubBundle\Repository\PasswordResetRepository")
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
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user", referencedColumnName="id")
     */
    protected $user;


    /**
     * @var string
     * @ORM\Column(type="string")
     */
    protected $hashedResetCode;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set hashedResetCode
     *
     * @param string $hashedResetCode
     * @return PasswordReset
     */
    public function setHashedResetCode($hashedResetCode)
    {
        $this->hashedResetCode = $hashedResetCode;

        return $this;
    }

    /**
     * Get hashedResetCode
     *
     * @return string
     */
    public function getHashedResetCode()
    {
        return $this->hashedResetCode;
    }

    /**
     * Set resetTime
     *
     * @param \DateTime $resetTime
     * @return PasswordReset
     */
    public function setResetTime($resetTime)
    {
        $this->resetTime = $resetTime;

        return $this;
    }

    /**
     * Get resetTime
     *
     * @return \DateTime
     */
    public function getResetTime()
    {
        return $this->resetTime;
    }

    /**
     * Set user
     *
     * @param \CodeClubBundle\Entity\User $user
     * @return PasswordReset
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \CodeClubBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

}