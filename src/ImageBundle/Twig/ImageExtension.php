<?php

namespace ImageBundle\Twig;

use CodeClubBundle\Service\ClubManager;
use Doctrine\ORM\EntityManager;

class ImageExtension extends \Twig_Extension
{
    protected $doctrine;
    protected $clubManager;
    public function __construct(EntityManager $manager, ClubManager $clubManager)
    {
        $this->doctrine = $manager;
        $this->clubManager = $clubManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ImageExtension';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('get_image', array($this, 'getImage')),
        );
    }
    public function getImage($name)
    {
        $club = $this->clubManager->getCurrentClub();
        $image = $this->doctrine->getRepository('ImageBundle:Image')->findByClubAndName($club, $name);
        if (!$image) {
            $defaultClub = $this->clubManager->getDefaultClub();
            $image = $this->doctrine->getRepository('ImageBundle:Image')->findByClubAndName($defaultClub, $name);
        }

        return $image;
    }
}
