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
        $this->logger->info('Host: '.$host);
        $baseHost = $this->baseHost;
        if ($host === $baseHost) {
            return;
        }

        $subdomain = str_replace('.'.$baseHost, '', $host);
        $this->logger->info('Subdomain: '.$subdomain);
        $club = $this->em->getRepository('CodeClubBundle:Club')->findOneBySubdomain($subdomain);
        if (!$club) {
            throw new NotFoundHttpException('Cannot find subdomain '.$subdomain);
        }

        $defaultClub = $this->em->getRepository('CodeClubBundle:Club')->findOneBySubdomain('default');

        $this->clubManager->setCurrentClub($club);
        $this->clubManager->setDefaultClub($defaultClub);
    }
}
