<?php

namespace CodeClubBundle\DataFixtures\ORM;

use CodeClubBundle\Entity\Club;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadClubData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $club = new Club();
        $club->setEmail('trondheim@kodeklubben.no');
        $club->setFacebook('kodeklubbentrondheim');
        $club->setName('Kodeklubben Trondheim');
        $club->setRegion('Trondheim');
        $club->setSubdomain('trondheim');

        $manager->persist($club);
        $manager->flush();

        $this->setReference('club-trondheim', $club);
    }

    public function getOrder()
    {
        return 1;
    }
}
