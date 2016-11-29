<?php

namespace CodeClubBundle\Service;

use AppBundle\Entity\Message;
use CodeClubBundle\Entity\Club;
use CourseBundle\Entity\Course;
use CourseBundle\Entity\CourseClass;
use CourseBundle\Entity\CourseType;
use ImageBundle\Entity\Image;
use StaticContentBundle\Entity\StaticContent;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use UserBundle\Entity\Child;
use UserBundle\Entity\Participant;
use UserBundle\Entity\Tutor;
use UserBundle\Entity\User;

class ClubManager
{
    private $currentClub;
    private $defaultClub;

    /**
     * @return Club
     */
    public function getCurrentClub()
    {
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

        if ($club !== $this->currentClub) {
            throw new AccessDeniedException();
        }
    }
}
