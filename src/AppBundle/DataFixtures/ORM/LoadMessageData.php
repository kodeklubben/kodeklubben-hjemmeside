<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Message;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadMessageData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $message1 = new Message();
        $message1->setMessage("Test Message 1");
        $manager->persist($message1);

        $message2 = new Message();
        $message2->setMessage("This is a very long message: Bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla");
        $manager->persist($message2);

        $manager->flush();
        $this->setReference('message-1', $message1);
        $this->setReference('message-2', $message2);
    }

    public function getOrder()
    {
        return 4;
    }
}