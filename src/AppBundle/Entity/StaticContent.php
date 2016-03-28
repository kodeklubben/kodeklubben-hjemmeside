<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Class
 *
 * @ORM\Table(name="static_content")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StaticContentRepository")
 * @UniqueEntity("idString")
 */
class StaticContent
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
     * @ORM\Column(name="id_string", type="string", unique=true)
     */
    private $idString;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="picture_url", type="string", nullable=true)
     */
    private $pictureUrl;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_edited", type="datetime")
     */
    private $lastEdited;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="last_edited_by_user_id", referencedColumnName="id")
     */
    private $lastEditedBy;

    /**
     * StaticContent constructor.
     */
    public function __construct()
    {
        $this->lastEdited = new \DateTime();
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
     * @return string
     */
    public function getIdString()
    {
        return $this->idString;
    }

    /**
     * @param string $idString
     */
    public function setIdString($idString)
    {
        $this->idString = $idString;
    }

    /**
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return string
     */
    public function getPictureUrl()
    {
        return $this->pictureUrl;
    }

    /**
     * @param string $pictureUrl
     */
    public function setPictureUrl($pictureUrl)
    {
        $this->pictureUrl = $pictureUrl;
    }

    /**
     * @return \DateTime
     */
    public function getLastEdited()
    {
        return $this->lastEdited;
    }

    /**
     * @param \DateTime $lastEdited
     */
    public function setLastEdited($lastEdited)
    {
        $this->lastEdited = $lastEdited;
    }

    /**
     * @return User
     */
    public function getLastEditedBy()
    {
        return $this->lastEditedBy;
    }

    /**
     * @param User $lastEditedBy
     */
    public function setLastEditedBy($lastEditedBy)
    {
        $this->lastEditedBy = $lastEditedBy;
    }
}

