<?php

namespace CourseBundle\Entity;

use CodeClubBundle\Entity\Club;
use Doctrine\ORM\Mapping as ORM;
use ImageBundle\Entity\Image;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Course.
 *
 * @ORM\Table(name="course_type", uniqueConstraints={
 *      @ORM\UniqueConstraint(name="course_type_name_idx", columns={"name"})
 * })
 * @UniqueEntity("name")
 * 
 * @ORM\Entity(repositoryClass="CourseBundle\Repository\CourseTypeRepository")
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
     * @var Club
     * 
     * @ORM\ManyToOne(targetEntity="\CodeClubBundle\Entity\Club")
     * @Assert\Valid
     */
    private $club;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Assert\NotBlank()
     */
    private $description;

    /**
     * @var Image
     *
     * @ORM\ManyToOne(targetEntity="ImageBundle\Entity\Image")
     * @Assert\Valid
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="challengeUrl", type="text", nullable=true)
     */
    private $challengesUrl;

    /**
     * @var bool
     *
     * @ORM\Column(name="deleted", type="boolean", nullable=true)
     */
    private $deleted;

    /**
     * @var bool
     *
     * @ORM\Column(name="hide_on_homepage", type="boolean", nullable=true)
     */
    private $hideOnHomepage;

    /**
     * @var Course[]
     *
     * @ORM\OneToMany(targetEntity="Course", mappedBy="courseType")
     * @Assert\Valid
     */
    private $courses;

    /**
     * Course constructor.
     */
    public function __construct()
    {
        $this->deleted = false;
        $this->hideOnHomepage = false;
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
     * Set name.
     *
     * @param string $name
     *
     * @return \CourseBundle\Entity\Course
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description.
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
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return Image
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param Image $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
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
     * Get imgUrl.
     *
     * @return string
     */
    public function getChallengesUrl()
    {
        return $this->challengesUrl;
    }

    /**
     * @return bool
     */
    public function isDeleted()
    {
        return $this->deleted;
    }

    /**
     * @param bool $deleted
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;
    }

    /**
     * Deletes the course type.
     */
    public function delete()
    {
        $this->deleted = true;
    }

    /**
     * @return bool
     */
    public function isHideOnHomepage()
    {
        return $this->hideOnHomepage;
    }

    /**
     * @param bool $hideOnHomepage
     */
    public function setHideOnHomepage($hideOnHomepage)
    {
        $this->hideOnHomepage = $hideOnHomepage;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Course[]
     */
    public function getCourses()
    {
        return $this->courses;
    }

    /**
     * @param Course[] $courses
     */
    public function setCourses($courses)
    {
        $this->courses = $courses;
    }
}
