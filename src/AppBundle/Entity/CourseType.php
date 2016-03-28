<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Course
 *
 * @ORM\Table(name="course_type")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CourseTypeRepository")
 */
class CourseType
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="imgUrl", type="text", nullable=true)
     */
    private $imgUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="challengeUrl", type="text", nullable=true)
     */
    private $challengesUrl;

    /**
     * Course constructor.
     */
    public function __construct()
    {
        $this->imgUrl = "http://placehold.it/800x500";
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
     * Set name
     *
     * @param string $name
     *
     * @return Course
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Course
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set imgUrl
     *
     * @param string $imgUrl
     *
     * @return Course
     */
    public function setImgUrl($imgUrl)
    {
        $this->imgUrl = $imgUrl;

        return $this;
    }

    /**
     * Get imgUrl
     *
     * @return string
     */
    public function getImgUrl()
    {
        return $this->imgUrl;
    }

    /**
     * Set imgUrl
     *
     * @param string $challengesUrl
     *
     * @return Course
     */
    public function setChallengesUrl($challengesUrl)
    {
        $this->challengesUrl = $challengesUrl;

        return $this;
    }

    /**
     * Get imgUrl
     *
     * @return string
     */
    public function getChallengesUrl()
    {
        return $this->challengesUrl;
    }

}

