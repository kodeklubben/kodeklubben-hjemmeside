<?php

namespace CodeClubBundle\Service;

use CodeClubBundle\Entity\Club;

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
}
