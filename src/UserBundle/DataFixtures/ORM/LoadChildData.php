<?php

namespace UserBundle\DataFixtures\ORM;

use UserBundle\Entity\Child;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadChildData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $child1 = new Child();
        $child1->setFirstName('Child1');
        $child1->setLastName('Child1-last');
        $child1->setParent($this->getReference('user-parent'));
        $manager->persist($child1);

        $child2 = new Child();
        $child2->setFirstName('Child2');
        $child2->setLastName('Child2-last');
        $child2->setParent($this->getReference('user-parent'));
        $manager->persist($child2);
        
        $manager->flush();

        $this->setReference('child1', $child1);
        $this->setReference('child2', $child2);
    }

    public function getOrder()
    {
        return 5;
    }
}