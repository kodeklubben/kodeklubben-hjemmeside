<?php

namespace ImageBundle\Twig;


use CodeClubBundle\Service\ClubFinder;
use Doctrine\Bundle\DoctrineBundle\Registry;

class ImageExtension extends \Twig_Extension
{
    protected $doctrine;
    protected $clubFinder;
    public function __construct(Registry $doctrine, ClubFinder $clubFinder){
        $this->doctrine = $doctrine;
        $this->clubFinder = $clubFinder;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ImageExtension';
    }


    public function getFunctions(){
        return array(
            'get_image' => new \Twig_Function_Method($this, 'getImage')
        );
    }
    public function getImage($name){
        $club = $this->clubFinder->getCurrentClub();
        $image = $this->doctrine->getRepository('ImageBundle:Image')->findByClubAndName($club, $name);
        return $image;
    }
}
