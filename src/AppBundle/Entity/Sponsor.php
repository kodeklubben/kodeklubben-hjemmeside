<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sponsor.
 *
 * @ORM\Table(name="sponsor", uniqueConstraints= {
 *      @ORM\UniqueConstraint(name="sponsor_club_name_idx", columns={"club_id", "name"})
 * })
 * @UniqueEntity({"club", "name"})
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SponsorRepository")
 */
class Sponsor
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
     * @ORM\ManyToOne(targetEntity="\AppBundle\Entity\Club", cascade={"persist"})
     * @Assert\Valid
     */
    private $club;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $url;

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
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }
    
    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }
    
    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    public function getImageName()
    {
        return "sponsor_{$this->id}";
    }
}
