<?php

namespace CodeClubBundle\Twig;
use CodeClubBundle\Service\ClubFinder;

class CodeClubExtension extends \Twig_Extension {
    protected $doctrine;
    protected $router;
    public function __construct($doctrine, $router){
        $this->doctrine = $doctrine;
        $this->router = $router;
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
        $clubFinder = new ClubFinder($this->doctrine, $this->router);
        return $clubFinder->getCurrentClub();
    }
}