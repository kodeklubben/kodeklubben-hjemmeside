<?php

namespace CodeClubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="CodeClubBundle\Repository\MessageRepository")
 */
class Message
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
     * @var Club
     *
     * @ORM\ManyToOne(targetEntity="\CodeClubBundle\Entity\Club")
     * @Assert\Valid
     */
    private $club;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     * @Assert\NotBlank()
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="timestamp", type="datetime")
     * @Assert\NotBlank()
     */
    private $timestamp;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expireDate", type="datetime")
     * @Assert\NotBlank()
     */
    private $expireDate;

    /**
     * Message constructor.
     */
    public function __construct()
    {
        $this->timestamp = new \DateTime();
        $expireDate = strtotime("+14 day");
        $this->expireDate = new \DateTime(date('Y-m-d H:m',$expireDate));
    }


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
     * @return Club
     */
    public function getClub()
    {
        return $this->club;
    }

    /**
     * @param Club $club
     */
    public function setClub($club)
    {
        $this->club = $club;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Message
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set timestamp
     *
     * @param \DateTime $timestamp
     *
     * @return Message
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp
     *
     * @return \DateTime
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * Set expireDate
     *
     * @param \DateTime $expireDate
     *
     * @return Message
     */
    public function setExpireDate($expireDate)
    {
        $this->expireDate = $expireDate;

        return $this;
    }

    /**
     * Get expireDate
     *
     * @return \DateTime
     */
    public function getExpireDate()
    {
        return $this->expireDate;
    }
}

