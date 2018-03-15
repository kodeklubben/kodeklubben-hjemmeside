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
        $lastYear = new \DateTime();
        $lastYear->modify('-1 year');

        $user1 = new User();
        $user1->setClub($this->getReference('club-trondheim'));
        $user1->setFirstName('Admin');
        $user1->setLastName('Adminsen');
        $user1->setPhone('12345678');
        $user1->setEmail('admin@mail.no');
        $user1->setUsername('admin@mail.no');
        $user1->setPassword('$2y$13$5jT9pYTBA/z5dCIKpQB3Nuz5wUfZcIF6NRcV0wAnWiGXMAqULMTHO');
        $user1->setRoles(array('ROLE_ADMIN'));
        $manager->persist($user1);

        $user2 = new User();
        $user2->setClub($this->getReference('club-trondheim'));
        $user2->setFirstName('Participant');
        $user2->setLastName('Participant');
        $user2->setPhone('12345678');
        $user2->setEmail('participant@mail.no');
        $user2->setUsername('participant@mail.no');
        $user2->setPassword('$2y$13$5jT9pYTBA/z5dCIKpQB3Nuz5wUfZcIF6NRcV0wAnWiGXMAqULMTHO');
        $user2->setRoles(array('ROLE_PARTICIPANT'));
        $manager->persist($user2);

        $userParent = new User();
        $userParent->setClub($this->getReference('club-trondheim'));
        $userParent->setFirstName('Parent');
        $userParent->setLastName('Parent');
        $userParent->setPhone('12345678');
        $userParent->setEmail('parent@mail.no');
        $userParent->setUsername('parent@mail.no');
        $userParent->setPassword('$2y$13$5jT9pYTBA/z5dCIKpQB3Nuz5wUfZcIF6NRcV0wAnWiGXMAqULMTHO');
        $userParent->setRoles(array('ROLE_PARENT'));
        $manager->persist($userParent);

        $user3 = new User();
        $user3->setClub($this->getReference('club-trondheim'));
        $user3->setFirstName('Tutor');
        $user3->setLastName('Tutor');
        $user3->setPhone('12345678');
        $user3->setEmail('tutor@mail.no');
        $user3->setUsername('tutor@mail.no');
        $user3->setPassword('$2y$13$5jT9pYTBA/z5dCIKpQB3Nuz5wUfZcIF6NRcV0wAnWiGXMAqULMTHO');
        $user3->setRoles(array('ROLE_TUTOR'));
        $manager->persist($user3);

        for ($i = 0; $i < 20; ++$i) {
            $participant = new User();
            if ($i % 4 == 0) {
                $participant->setCreatedDatetime($lastYear);
            }
            $participant->setClub($this->getReference('club-trondheim'));
            $participant->setFirstName('Participant');
            $participant->setLastName($i);
            $participant->setPhone('12345678');
            $participant->setEmail("particpipant{$i}@mail.no");
            $participant->setPassword('secret');
            $participant->setRoles(array('ROLE_PARTICIPANT'));
            $manager->persist($participant);
            $this->setReference('user-participant-'.$i, $participant);
        }

        for ($i = 0; $i < 30; ++$i) {
            $parent = new User();
            if ($i % 4 == 0) {
                $parent->setCreatedDatetime($lastYear);
            }
            $parent->setClub($this->getReference('club-trondheim'));
            $parent->setFirstName('Parent');
            $parent->setLastName($i);
            $parent->setPhone('12345678');
            $parent->setEmail("parent{$i}@mail.no");
            $parent->setPassword('secret');
            $parent->setRoles(array('ROLE_PARENT'));
            $manager->persist($parent);
            $this->setReference('user-parent-'.$i, $parent);
        }

        for ($i = 0; $i < 20; ++$i) {
            $tutor = new User();
            if ($i % 4 == 0) {
                $tutor->setCreatedDatetime($lastYear);
            }
            $tutor->setClub($this->getReference('club-trondheim'));
            $tutor->setFirstName('Tutor');
            $tutor->setLastName($i);
            $tutor->setPhone('12345678');
            $tutor->setEmail("tutor{$i}@mail.no");
            $tutor->setPassword('secret');
            $tutor->setRoles(array('ROLE_TUTOR'));
            $manager->persist($tutor);
            $this->setReference('user-tutor-'.$i, $tutor);
        }

        $manager->flush();

        $this->setReference('user-admin', $user1);
        $this->setReference('user-participant', $user2);
        $this->setReference('user-parent', $userParent);
        $this->setReference('user-tutor', $user3);
    }

    public function getOrder()
    {
        return 3;
    }
}
