<?php

namespace AppBundle\Twig;

class UserExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('role', array($this, 'translateRoleFilter')),
        );
    }

    public function translateRoleFilter($role)
    {
        $roleTranslate = array(
            'ROLE_PARTICIPANT' => 'Deltaker',
            'ROLE_PARENT' => 'Foresatt',
            'ROLE_TUTOR' => 'Veileder',
            'ROLE_ADMIN' => 'Admin',
        );
        if (!in_array($role, array_keys($roleTranslate))) {
            return 'Ukjent brukertype';
        }

        return $roleTranslate[$role];
    }

    public function getName()
    {
        return 'user_extension';
    }
}
