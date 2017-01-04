<?php

namespace UserBundle\Security;

use CodeClubBundle\Service\ClubManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use UserBundle\Entity\User;

class UserProvider implements UserProviderInterface
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var ClubManager
     */
    private $clubManager;

    /**
     * UserProvider constructor.
     *
     * @param EntityManager $em
     * @param ClubManager   $clubManager
     */
    public function __construct(EntityManager $em, ClubManager $clubManager)
    {
        $this->em = $em;
        $this->clubManager = $clubManager;
    }

    /**
     * Loads the user for the given username.
     *
     * This method must throw UsernameNotFoundException if the user is not
     * found.
     *
     * @param string $username The username
     *
     * @return UserInterface
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($username)
    {
        $currentClub = $this->clubManager->getCurrentClub();
        $user = $this->em->getRepository('UserBundle:User')->findByUsernameAndClub($username, $currentClub);

        if ($user === null) {
            throw new UsernameNotFoundException(
                sprintf('Username "%s" does not exist.', $username)
            );
        }

        return $user;
    }

    /**
     * Refreshes the user for the account interface.
     *
     * It is up to the implementation to decide if the user data should be
     * totally reloaded (e.g. from the database), or if the UserInterface
     * object can just be merged into some internal array of users / identity
     * map.
     *
     * @param UserInterface $user
     *
     * @return UserInterface
     *
     * @throws UnsupportedUserException if the account is not supported
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * Whether this provider supports the given user class.
     *
     * @param string $class
     *
     * @return bool
     */
    public function supportsClass($class)
    {
        return $class === 'UserBundle\Entity\User';
    }
}
