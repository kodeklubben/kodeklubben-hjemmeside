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
        $message1->setMessage('Test Message 1');
        $message1->setClub($this->getReference('club-trondheim'));
        $manager->persist($message1);

        $message2 = new Message();
        $message2->setMessage('This is a very long message: Bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla');
        $message2->setClub($this->getReference('club-trondheim'));
        $message2->getTimestamp()->modify('-1day');
        $manager->persist($message2);

        $message3 = new Message();
        $message3->setMessage('This is a super long message: Bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla bla');
        $message3->setClub($this->getReference('club-trondheim'));
        $manager->persist($message3);

        $manager->flush();
        $this->setReference('message-1', $message1);
        $this->setReference('message-2', $message2);
        $this->setReference('message-3', $message3);
    }

    public function getOrder()
    {
        return 4;
    }
}
