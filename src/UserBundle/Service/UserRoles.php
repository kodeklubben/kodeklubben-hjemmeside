<?php

namespace UserBundle\Service;

class UserRoles
{
    private $userRoles;

    /**
     * UserRoles constructor.
     *
     * @param $userRoles
     */
    public function __construct($userRoles)
    {
        $this->userRoles = $userRoles;
    }

    public function isValidRole($role)
    {
        $validRoles = array_keys($this->userRoles);

        return in_array($role, $validRoles);
    }
}
