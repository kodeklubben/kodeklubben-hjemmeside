<?php

namespace CodeClubBundle\EventListener;

use CodeClubBundle\Service\ClubManager;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CurrentClubListener
{
    private $clubManager;
    private $em;
    private $baseHost;
    private $logger;

    /**
     * CurrentClubListener constructor.
     *
     * @param ClubManager   $clubManager
     * @param EntityManager $em
     * @param string        $baseHost
     * @param Logger        $logger
     */
    public function __construct(ClubManager $clubManager, EntityManager $em, string $baseHost, Logger $logger)
    {
        $this->clubManager = $clubManager;
        $this->em = $em;
        $this->baseHost = $baseHost;
        $this->logger = $logger;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $host = $request->getHost();
        $baseHost = $this->baseHost;
        if ($host === $baseHost) {
            throw new NotFoundHttpException('Main page not implemented yet');
        }

        $subdomain = str_replace('.'.$baseHost, '', $host);
        $club = $this->em->getRepository('CodeClubBundle:Club')->findOneBySubdomain($subdomain);
        if (!$club) {
            throw new NotFoundHttpException('Cannot find subdomain '.$subdomain);
        }

        $defaultClub = $this->em->getRepository('CodeClubBundle:Club')->findOneBySubdomain('default');

        $this->clubManager->setCurrentClub($club);
        $this->clubManager->setDefaultClub($defaultClub);
    }
}
