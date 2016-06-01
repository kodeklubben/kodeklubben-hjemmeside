<?php

namespace CodeClubBundle\Twig;
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
        $host = $this->router->getContext()->getHost();
        $subdomain = substr($host, 0, strpos($host, '.'));
        $club = $this->doctrine
            ->getRepository('CodeClubBundle:Club')
            ->findBySubdomain($subdomain);
        return $club;
    }
}