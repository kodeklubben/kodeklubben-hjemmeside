<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class.
 *
 * @ORM\Table(name="course_class")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CourseClassRepository")
 */
class CourseClass implements \JsonSerializable
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
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="datetime")
     * @Assert\DateTime()
     */
    private $time;

    /**
     * @var string
     *
     * @ORM\Column(name="place", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $place;

    /**
     * @var \AppBundle\Entity\Course
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Course", inversedBy="classes")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id", onDelete="CASCADE")
     * @Assert\Valid
     */
    private $course;

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
     * Set time.
     *
     * @param \DateTime $time
     */
    public function setTime($time)
    {
        $this->time = $time;
    }

    /**
     * Get time.
     *
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set place.
     *
     * @param string $place
     */
    public function setPlace($place)
    {
        $this->place = $place;
    }

    /**
     * Get place.
     *
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
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
    public function setCourse(Course $course)
    {
        $this->course = $course;
    }

    public function getDayNorwegian()
    {
        $norwegianWeek = array(
            1 => 'Mandag',
            2 => 'Tirsdag',
            3 => 'Onsdag',
            4 => 'Torsdag',
            5 => 'Fredag',
            6 => 'Lørdag',
            7 => 'Søndag',
        );

        return $norwegianWeek[$this->time->format('N')];
    }

    public function jsonSerialize()
    {
        return array(
            'day' => $this->getDayNorwegian(),
            'place' => $this->place,
            'course' => $this->course->getName(),
            'courseId' => $this->course->getId(),
            'time' => $this->time->format('H:i'),
            'datetime' => $this->time,
        );
    }
}
