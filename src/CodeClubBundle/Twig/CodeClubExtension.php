<?php

namespace CodeClubBundle\Twig;

use CodeClubBundle\Service\ClubManager;

class CodeClubExtension extends \Twig_Extension
{
    protected $clubManager;

    public function __construct(ClubManager $clubManager)
    {
        $this->clubManager = $clubManager;
    }

    public function getName()
    {
        return 'ClubExtension';
    }

    public function getFunctions()
    {
        return array(
            'get_club' => new \Twig_Function_Method($this, 'getClub'),
        );
    }

    public function getClub()
    {
        return $this->clubManager->getCurrentClub();
    }
}
