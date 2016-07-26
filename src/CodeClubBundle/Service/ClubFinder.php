<?php

namespace CodeClubBundle\Service;
class ClubFinder {
    protected $doctrine;
    protected $router;
    public function __construct($doctrine, $router){
        $this->doctrine = $doctrine;
        $this->router = $router;
    }

    public function getCurrentClub(){
//        $host = $this->router->getContext()->getHost();
//        $subdomain = substr($host, 0, strpos($host, '.'));
        $subdomain = 'trondheim';//TODO use subdomain from host
        $club = $this->doctrine
            ->getRepository('CodeClubBundle:Club')
            ->findOneBySubdomain($subdomain);
        return $club;
    }
}