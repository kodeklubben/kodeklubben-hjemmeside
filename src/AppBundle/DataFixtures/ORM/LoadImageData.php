<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Image;

class LoadImageData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $club = $this->getReference('club-trondheim');

        $manager->persist($this->createImage($club, 'header', 'header.jpg'));
        $manager->persist($this->createImage($club, 'participant', 'deltaker.jpg'));
        $manager->persist($this->createImage($club, 'tutor', 'veileder.jpg'));
        $manager->persist($this->createImage($club, 'about', 'about.jpg'));
        $manager->persist($this->createImage($club, 'about_social', 'about_social.jpg'));
        $manager->persist($this->createImage($club, 'scratch', 'scratch.png'));
        $manager->persist($this->createImage($club, 'python', 'python.png'));
        $manager->persist($this->createImage($club, 'minecraft', 'minecraft.png'));
        $manager->persist($this->createImage($club, 'java', 'java.png'));

        $club = $this->getReference('club-default');

        $manager->persist($this->createImage($club, 'header', 'header.jpg'));
        $manager->persist($this->createImage($club, 'participant', 'deltaker.jpg'));
        $manager->persist($this->createImage($club, 'tutor', 'veileder.jpg'));
        $manager->persist($this->createImage($club, 'about', 'about.jpg'));
        $manager->persist($this->createImage($club, 'about_social', 'about_social.jpg'));
        $manager->persist($this->createImage($club, 'scratch', 'scratch.png'));
        $manager->persist($this->createImage($club, 'python', 'python.png'));
        $manager->persist($this->createImage($club, 'minecraft', 'minecraft.png'));
        $manager->persist($this->createImage($club, 'java', 'java.png'));

        $manager->flush();
    }

    private function createImage($club, $name, $fileName)
    {
        $image = new Image();
        $image->setClub($club);
        $image->setName($name);
        $image->setFileName($fileName);
        $image->setFilePath('/img/club/default/'.$fileName);
        $this->setReference('img-'.$name, $image);

        return $image;
    }

    public function getOrder()
    {
        return 2;
    }
}
