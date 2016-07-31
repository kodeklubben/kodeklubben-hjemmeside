<?php

namespace CodeClubBundle\Twig;
use CodeClubBundle\Service\ClubFinder;

class CodeClubExtension extends \Twig_Extension {
    protected $doctrine;
    protected $clubFinder;

    public function __construct($doctrine, ClubFinder $clubFinder){
        $this->doctrine = $doctrine;
        $this->clubFinder = $clubFinder;
    }
    
    public function getName(){
        return "ClubExtension";
    }

    public function getFunctions(){
        return array(
            'get_club' => new \Twig_Function_Method($this, 'getClub')
        );
    }

    public function getClub(){
        return $this->clubFinder->getCurrentClub();
    }
}