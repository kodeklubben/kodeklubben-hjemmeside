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
        $clubTrondheim = new Club();
        $clubTrondheim->setEmail('trondheim@kodeklubben.no');
        $clubTrondheim->setFacebook('kodeklubbentrondheim');
        $clubTrondheim->setName('Kodeklubben Trondheim');
        $clubTrondheim->setRegion('Trondheim');
        $clubTrondheim->setSubdomain('trondheim');
        $manager->persist($clubTrondheim);

        $clubOslo = new Club();
        $clubOslo->setEmail('oslo@kodeklubben.no');
        $clubOslo->setFacebook('kodeklubbenoslo');
        $clubOslo->setName('Kodeklubben Oslo');
        $clubOslo->setRegion('Oslo');
        $clubOslo->setSubdomain('oslo');
        $manager->persist($clubOslo);

        $clubDefault = new Club();
        $clubDefault->setEmail('default@kodeklubben.no');
        $clubDefault->setFacebook('kodeklubbendefault');
        $clubDefault->setName('Kodeklubben Default');
        $clubDefault->setRegion('Default');
        $clubDefault->setSubdomain('default');
        $manager->persist($clubDefault);

        $manager->flush();

        $this->setReference('club-trondheim', $clubTrondheim);
        $this->setReference('club-oslo', $clubOslo);
        $this->setReference('club-default', $clubDefault);
    }

    public function getOrder()
    {
        return 1;
    }
}
