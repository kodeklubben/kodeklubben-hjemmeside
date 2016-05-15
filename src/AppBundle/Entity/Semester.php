<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class
 *
 * @ORM\Table(name="semester")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SemesterRepository")
 */
class Semester
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
     * @var boolean
     *
     * @ORM\Column(name="is_spring", type="boolean")
     */
    private $isSpring;

    /**
     * @var string
     *
     * @ORM\Column(name="year", type="integer", length=4)
     */
    private $year;


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
     * @return boolean
     */
    public function getIsSpring()
    {
        return $this->isSpring;
    }

    /**
     * @return bool
     */
    public function isSpring()
    {
        return $this->isSpring;
    }

    /**
     * @param boolean $isSpring
     */
    public function setIsSpring($isSpring)
    {
        $this->isSpring = $isSpring;
    }

    /**
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param string $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @return \DateTime
     */
    public function getStartTime()
    {
        $month = $this->isSpring ? '01' : '08';
        return new \DateTime($this->year . $month . "01 00:00:00");
    }

    /**
     * @return \DateTime
     */
    public function getEndTime()
    {
        $month = $this->isSpring ? '07' : '12';
        return new \DateTime($this->year . $month . "31 23:59:59");
    }

    public function __toString()
    {
        return ($this->isSpring ? 'Vår ' : 'Høst ') . $this->year;
    }

}

