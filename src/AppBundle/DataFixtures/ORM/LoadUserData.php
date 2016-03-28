<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setFirstName('Admin');
        $user1->setLastName('Adminsen');
        $user1->setPhone('12345678');
        $user1->setEmail('admin@mail.no');
        $user1->setUsername('admin@mail.no');
        $user1->setPassword('$2y$13$5jT9pYTBA/z5dCIKpQB3Nuz5wUfZcIF6NRcV0wAnWiGXMAqULMTHO');
        $user1->setRoles(array('ROLE_ADMIN'));
        $manager->persist($user1);

        $user2 = new User();
        $user2->setFirstName('Participant');
        $user2->setLastName('Participant');
        $user2->setPhone('12345678');
        $user2->setEmail('participant@mail.no');
        $user2->setUsername('participant@mail.no');
        $user2->setPassword('$2y$13$5jT9pYTBA/z5dCIKpQB3Nuz5wUfZcIF6NRcV0wAnWiGXMAqULMTHO');
        $user2->setRoles(array('ROLE_PARTICIPANT'));
        $manager->persist($user2);

        $user3 = new User();
        $user3->setFirstName('Tutor');
        $user3->setLastName('Tutor');
        $user3->setPhone('12345678');
        $user3->setEmail('tutor@mail.no');
        $user3->setUsername('tutor@mail.no');
        $user3->setPassword('$2y$13$5jT9pYTBA/z5dCIKpQB3Nuz5wUfZcIF6NRcV0wAnWiGXMAqULMTHO');
        $user3->setRoles(array('ROLE_TUTOR'));
        $manager->persist($user3);

        $manager->flush();

        $this->setReference('user-admin', $user1);
        $this->setReference('user-participant', $user2);
        $this->setReference('user-tutor', $user3);
    }

    public function getOrder()
    {
        return 4;
    }
}