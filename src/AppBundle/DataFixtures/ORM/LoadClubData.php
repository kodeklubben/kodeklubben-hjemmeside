<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Club;
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
        $manager->persist($clubTrondheim);

        $clubOslo = new Club();
        $clubOslo->setEmail('oslo@kodeklubben.no');
        $clubOslo->setFacebook('kodeklubbenoslo');
        $clubOslo->setName('Kodeklubben Oslo');
        $clubOslo->setRegion('Oslo');
        $manager->persist($clubOslo);

        $clubDefault = new Club();
        $clubDefault->setEmail('default@kodeklubben.no');
        $clubDefault->setFacebook('kodeklubbendefault');
        $clubDefault->setName('Kodeklubben Default');
        $clubDefault->setRegion('Default');
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
