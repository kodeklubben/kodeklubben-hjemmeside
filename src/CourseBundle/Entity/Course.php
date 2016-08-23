<?php

namespace CourseBundle\Entity;

use UserBundle\Entity\Participant;
use CodeClubBundle\Entity\Semester;
use UserBundle\Entity\Tutor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Course.
 *
 * @ORM\Table(name="course")
 * @ORM\Entity(repositoryClass="CourseBundle\Repository\CourseRepository")
 */
class Course
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
     * @var Semester
     *
     * @ORM\ManyToOne(targetEntity="CodeClubBundle\Entity\Semester")
     * @ORM\JoinColumn(name="semester_id", referencedColumnName="id")
     * @Assert\Valid
     */
    private $semester;

    /**
     * @var CourseType
     *
     * @ORM\ManyToOne(targetEntity="CourseType", inversedBy="courses")
     * @ORM\JoinColumn(name="course_type_id", referencedColumnName="id")
     * @Assert\Valid
     */
    private $courseType;

    /**
     * @var int
     *
     * @ORM\Column(name="participant_limit", type="integer")
     * @Assert\NotBlank()
     */
    private $participantLimit;

    /**
     * @var Tutor[]
     *
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\Tutor", mappedBy="course")
     */
    private $tutors;

    /**
     * @var Participant[]
     *
     * @ORM\OneToMany(targetEntity="UserBundle\Entity\Participant", mappedBy="course")
     */
    private $participants;

    /**
     * @var CourseClass[]
     *
     * @ORM\OneToMany(targetEntity="CourseClass", mappedBy="course")
     */
    private $classes;

    /**
     * @var bool
     *
     * @ORM\Column(name="deleted", type="boolean", nullable=true)
     */
    private $deleted;

    /**
     * Course constructor.
     */
    public function __construct()
    {
        $this->classes = new ArrayCollection();
        $this->tutors = new ArrayCollection();
        $this->substitutes = new ArrayCollection();
        $this->deleted = false;
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
     * Set name.
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
     * @return Semester
     */
    public function getSemester()
    {
        return $this->semester;
    }

    /**
     * @param Semester $semester
     */
    public function setSemester($semester)
    {
        $this->semester = $semester;
    }

    /**
     * @return CourseType
     */
    public function getCourseType()
    {
        return $this->courseType;
    }

    /**
     * @param CourseType $courseType
     */
    public function setCourseType($courseType)
    {
        $this->courseType = $courseType;
    }

    /**
     * @return int
     */
    public function getParticipantLimit()
    {
        return $this->participantLimit;
    }

    /**
     * @param int $participantLimit
     */
    public function setParticipantLimit($participantLimit)
    {
        $this->participantLimit = $participantLimit;
    }

    /**
     * @return Tutor[]
     */
    public function getTutors()
    {
        return $this->tutors;
    }

    /**
     * @param Tutor[] $tutors
     */
    public function setTutors($tutors)
    {
        $this->tutors = $tutors;
    }

    /**
     * @param Tutor $tutor
     */
    public function removeTutor($tutor)
    {
        $this->tutors->removeElement($tutor);
    }

    /**
     * @param Tutor $tutor
     */
    public function addTutor($tutor)
    {
        $this->tutors[] = $tutor;
    }

    /**
     * @return Participant[]
     */
    public function getParticipants()
    {
        return $this->participants;
    }

    /**
     * @param Participant[] $participants
     */
    public function setParticipants($participants)
    {
        $this->participants = $participants;
    }

    /**
     * @return CourseClass[]
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * @param CourseClass[] $classes
     */
    public function setClasses($classes)
    {
        $this->classes = $classes;
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
     * @return Tutor[]
     */
    public function getSubstitutes()
    {
        $substitutes = array();
        foreach ($this->tutors as $tutor) {
            if ($tutor->isIsSubstitute()) {
                $substitutes[] = $tutor;
            }
        }

        return $substitutes;
    }

    /**
     * @return Tutor[]
     */
    public function getFullTimeTutors()
    {
        $tutors = array();
        foreach ($this->tutors as $tutor) {
            if (!$tutor->isIsSubstitute()) {
                $tutors[] = $tutor;
            }
        }

        return $tutors;
    }

    /*
     * @return float
     */
    public function getParticipantFullTimeTutorRatio()
    {
        $tutor_count = count($this->getFullTimeTutors());
        if ($tutor_count == 0) {
            return INF;
        }

        return count($this->participants) / floatval($tutor_count);
    }

    public function __toString()
    {
        return $this->name;
    }
}
