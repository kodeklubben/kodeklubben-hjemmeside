<?php

namespace ClubManagerBundle\EventListener;

use CodeClubBundle\Service\ClubManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;

class ClubManagerPathListener
{
    private $clubManager;
    private $router;

    /**
     * CurrentClubListener constructor.
     *
     * @param ClubManager     $clubManager
     * @param RouterInterface $router
     */
    public function __construct(ClubManager $clubManager, RouterInterface $router)
    {
        $this->clubManager = $clubManager;
        $this->router = $router;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $requestUri = $request->getRequestUri();
        $club = $this->clubManager->getCurrentClub();
        if ($club === null && $requestUri === '/') {
            $response = new RedirectResponse($this->router->generate('clubs'));
            $event->setResponse($response);
        }
    }
}
