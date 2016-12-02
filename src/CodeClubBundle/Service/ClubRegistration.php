<?php

namespace CodeClubBundle\Service;

use CodeClubBundle\Entity\Club;
use UserBundle\Service\UserRegistration;

class ClubRegistration
{
    private $mailer;
    private $userRegistration;
    private $baseHost;
    private $mail;

    /**
     * ClubRegistration constructor.
     *
     * @param \Swift_Mailer    $mailer
     * @param UserRegistration $userRegistration
     * @param string           $baseHost
     * @param $mail
     */
    public function __construct(\Swift_Mailer $mailer, UserRegistration $userRegistration, $baseHost, $mail)
    {
        $this->mailer = $mailer;
        $this->userRegistration = $userRegistration;
        $this->baseHost = $baseHost;
        $this->mail = $mail;
    }

    /**
     * @param Club $club
     */
    public function createAdminAndSendRegistrationEmail(Club $club)
    {
        $userRegistration = $this->userRegistration;
        $adminUser = $userRegistration->newUser($club);
        $adminUser->setEmail($club->getEmail());
        $adminUser->setFirstName('Admin');
        $adminUser->setLastName($club->getName());
        $adminUser->setPhone('-');
        $adminUser->setRoles(['ROLE_ADMIN']);

        $password = $userRegistration->setRandomEncodedPassword($adminUser);
        $userRegistration->persistUser($adminUser);

        /*@var \Swift_Mime_Message*/
        $emailMessage = \Swift_Message::newInstance()
            ->setSubject('Ny kodeklubb - '.$club->getName())
            ->setFrom(array($this->mail => $this->baseHost))
            ->setTo($club->getEmail())
            ->setBody("
            {$club->getName()} har blitt opprettet på http://{$club->getSubdomain()}.{$this->baseHost}\r\n
            Her er din adminbruker:\r\n
            Brukernavn: {$adminUser->getEmail()}\r\n
            Passord: {$password}\r\n
            ");

        $this->mailer->send($emailMessage);
    }
}
