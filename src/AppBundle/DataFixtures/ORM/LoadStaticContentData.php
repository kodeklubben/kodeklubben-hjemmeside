<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\StaticContent;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadStaticContentData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $sc1 = new StaticContent();
        $sc1->setIdString('region');
        $sc1->setContent('Trondheim');
        $sc1->setLastEditedBy($this->getReference('user-admin'));

        $manager->persist($sc1);

        $sc2 = new StaticContent();
        $sc2->setIdString('email');
        $sc2->setContent('trondheim@kodeklubben.no');
        $sc2->setLastEditedBy($this->getReference('user-admin'));

        $manager->persist($sc2);


        $manager->flush();

        $this->setReference('sc-region', $sc1);
        $this->setReference('sc-email', $sc2);
    }

    public function getOrder()
    {
        return 5;
    }
}