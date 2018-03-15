<?php

namespace AppBundle\Twig;

use AppBundle\Service\ClubManager;

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
            new \Twig_SimpleFunction('get_club', array($this, 'getClub')),
        );
    }

    public function getClub()
    {
        return $this->clubManager->getCurrentClub();
    }
}
