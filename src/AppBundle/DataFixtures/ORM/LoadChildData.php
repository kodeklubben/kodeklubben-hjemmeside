<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Child;

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

        for ($i = 0; $i < 50; ++$i) {
            $child = new Child();
            $child->setFirstName('Child');
            $child->setLastName($i);
            $parent = 'user-parent-'.intdiv($i, 2);
            $child->setParent($this->getReference($parent));
            $this->setReference('child-'.$i, $child);
            $manager->persist($child);
        }

        $manager->flush();

        $this->setReference('child1', $child1);
        $this->setReference('child2', $child2);
    }

    public function getOrder()
    {
        return 5;
    }
}
