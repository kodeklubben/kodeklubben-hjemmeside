<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Sponsor;

class SponsorData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        foreach (['club-trondheim', 'club-oslo', 'club-default'] as $clubRef) {
            $club = $this->getReference($clubRef);

            $manager->persist($this->createSponsor($club, "Sponsor 1, {$clubRef}", "sponsor1.png"));
            $manager->persist($this->createSponsor($club, "Sponsor 2, {$clubRef}", "sponsor2.png"));
            $manager->persist($this->createSponsor($club, "Sponsor 3, {$clubRef}", "sponsor3.png"));

            $manager->flush();
        }
    }

    private function createSponsor($club, $name, $fileName)
    {
        $sponsor = new Sponsor();
        $sponsor->setClub($club);
        $sponsor->setName($name);
        $sponsor->setUrl("http://sponsor.no/{$name}");
        $this->setReference('sponsor-'.$name, $sponsor);

        return $sponsor;
    }

    public function getOrder()
    {
        return 4;
    }
}
