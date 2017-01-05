<?php

namespace CodeClubBundle\Service;

use AppBundle\Entity\Message;
use CodeClubBundle\Entity\Club;
use CourseBundle\Entity\Course;
use CourseBundle\Entity\CourseClass;
use CourseBundle\Entity\CourseType;
use Doctrine\ORM\EntityManager;
use ImageBundle\Entity\Image;
use StaticContentBundle\Entity\StaticContent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use UserBundle\Entity\Child;
use CourseBundle\Entity\Participant;
use CourseBundle\Entity\Tutor;
use UserBundle\Entity\User;

class ClubManager
{
    private $currentClub;
    private $defaultClub;
    private $em;

    /**
     * ClubManager constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @return Club
     */
    public function getCurrentClub()
    {
        // Remove this line for multidomain support
        $this->currentClub = $this->em->getRepository('CodeClubBundle:Club')->findOneBySubdomain('trondheim');

        return $this->currentClub;
    }

    /**
     * @param Club $currentClub
     */
    public function setCurrentClub(Club $currentClub)
    {
        $this->currentClub = $currentClub;
    }

    /**
     * @return Club
     */
    public function getDefaultClub()
    {
        return $this->defaultClub;
    }

    /**
     * @param Club $defaultClub
     */
    public function setDefaultClub(Club $defaultClub)
    {
        $this->defaultClub = $defaultClub;
    }

    /**
     * Checks if course belongs to current club.
     *
     * @param CourseClass|Course|CourseType|User|Club|Participant|Tutor|Child $obj
     */
    public function denyIfNotCurrentClub($obj)
    {
        $club = null;
        if ($obj instanceof CourseClass) {
            $club = $obj->getCourse()->getCourseType()->getClub();
        } elseif ($obj instanceof Course) {
            $club = $obj->getCourseType()->getClub();
        } elseif (
            $obj instanceof CourseType ||
            $obj instanceof User ||
            $obj instanceof Image ||
            $obj instanceof Message ||
            $obj instanceof StaticContent) {
            $club = $obj->getClub();
        } elseif (
            $obj instanceof Participant ||
            $obj instanceof Tutor ||
            $obj instanceof Child) {
            $club = $obj->getUser()->getClub();
        } elseif ($obj instanceof Club) {
            $club = $obj;
        }

        if ($club !== $this->getCurrentClub()) {
            throw new AccessDeniedException();
        }
    }
}
