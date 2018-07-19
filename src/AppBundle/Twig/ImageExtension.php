<?php

namespace AppBundle\Twig;

use AppBundle\Service\ClubManager;
use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Image;
use AppBundle\Entity\Sponsor;

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
            new \Twig_SimpleFunction('get_sponsor_image', array($this, 'getSponsorImage')),
        );
    }
    public function getImage($name)
    {
        $club = $this->clubManager->getCurrentClub();
        $image = $this->doctrine->getRepository(Image::class)->findByClubAndName($club, $name);
        if (!$image) {
            $defaultClub = $this->clubManager->getDefaultClub();
            $image = $this->doctrine->getRepository(Image::class)->findByClubAndName($defaultClub, $name);
        }

        return $image;
    }
    public function getSponsorImage($id)
    {
        $club = $this->clubManager->getCurrentClub();
        $sponsor = $this->doctrine
            ->getRepository(Sponsor::class)
            ->findById($id);
        return $this->getImage($sponsor->getImageName());
    }
}
