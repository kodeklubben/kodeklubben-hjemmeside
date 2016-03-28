<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class
 *
 * @ORM\Table(name="course_class")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CourseClassRepository")
 */
class CourseClass
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
     */
    private $time;

    /**
     * @var string
     *
     * @ORM\Column(name="place", type="string", length=255)
     */
    private $place;

    /**
     * @var CourseSeries
     *
     * @ORM\ManyToOne(targetEntity="CourseSeries")
     * @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     */
    private $courseSeries;


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
     * Set time
     *
     * @param \DateTime $time
     *
     * @return Class
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set place
     *
     * @param string $place
     *
     * @return Class
     */
    public function setPlace($place)
    {
        $this->place = $place;

        return $this;
    }

    /**
     * Get place
     *
     * @return string
     */
    public function getPlace()
    {
        return $this->place;
    }

    /**
     * @return CourseSeries
     */
    public function getCourseSeries()
    {
        return $this->courseSeries;
    }

    /**
     * @param CourseSeries $courseSeries
     */
    public function setCourseSeries(CourseSeries $courseSeries)
    {
        $this->courseSeries = $courseSeries;
    }


}

