<?php

namespace UserBundle\Service;

use CodeClubBundle\Service\ClubManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use UserBundle\Entity\User;

class UserRegistration
{
    private $passwordEncoder;
    private $manager;
    private $clubManager;
    private $twig;
    private $mailer;

    /**
     * UserRegistration constructor.
     *
     * @param UserPasswordEncoder $passwordEncoder
     * @param EntityManager       $manager
     * @param ClubManager         $clubManager
     * @param \Twig_Environment   $twig
     * @param \Swift_Mailer       $mailer
     *
     * @internal param ClubFinder $clubFinder
     */
    public function __construct(UserPasswordEncoder $passwordEncoder, EntityManager $manager, ClubManager $clubManager,
                                \Twig_Environment $twig, \Swift_Mailer $mailer)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->doctrine = $manager;
        $this->clubManager = $clubManager;
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    /**
     * @param User $user
     */
    public function persistUser(User $user)
    {
        $this->manager->persist($user);
        $this->manager->flush();
    }

    /**
     * @param User $user
     *
     * @return int
     */
    public function persistUserAndSendNewUserCode(User $user)
    {
        // Set new user code
        $code = $this->setNewUserCode($user);

        // Save user to database
        $this->persistUser($user);

        $club = $user->getClub();

        /*@var \Swift_Mime_Message*/
        $emailMessage = \Swift_Message::newInstance()
            ->setSubject('Ny bruker/New user '.$club->getName())
            ->setFrom(array($club->getEmail() => $club->getName()))
            ->setTo($user->getEmail())
            ->setBody($this->twig->render('@User/new_user_code_email.html.twig', array(
                'user' => $user,
                'code' => $code,
            )));

        return $this->mailer->send($emailMessage);
    }

    /**
     * @param User $user
     *
     * @return string newUserCode
     */
    public function setNewUserCode(User $user)
    {
        $code = $this->generateNewUserCode();
        $hashedCode = $this->hashNewUserCode($code);
        $user->setNewUserCode($hashedCode);

        return $code;
    }

    /**
     * @param string $code
     *
     * @return string hashedNewUserCode
     */
    public function hashNewUserCode($code)
    {
        return hash('sha512', $code, false);
    }

    /**
     * @return string randomCode
     */
    public function generateNewUserCode()
    {
        return bin2hex(openssl_random_pseudo_bytes(16));
    }

    /**
     * @param int $length
     *
     * @return string randomPassword
     */
    public function generateRandomPassword($length)
    {
        return substr(hash('sha512', rand()), 0, $length);
    }

    /**
     * @param User $user
     */
    public function setRandomEncodedPassword(User $user)
    {
        $password = $this->encodePassword($user, $this->generateRandomPassword(16));
        $user->setPassword($password);
    }

    /**
     * @param User   $user
     * @param string $password
     *
     * @return string encodedPassword
     */
    public function encodePassword(User $user, $password)
    {
        return $this->passwordEncoder->encodePassword($user, $password);
    }

    /**
     * @return User
     */
    public function newUser()
    {
        $user = new User();
        $user->setClub($this->clubManager->getCurrentClub());

        return $user;
    }
}
