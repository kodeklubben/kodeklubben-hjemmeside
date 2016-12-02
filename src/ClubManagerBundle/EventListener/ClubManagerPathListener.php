<?php

namespace ClubManagerBundle\EventListener;

use CodeClubBundle\Service\ClubManager;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class ClubManagerPathListener
{
    private $clubManager;
    private $router;
    private $logger;
    private $requestUri;

    /**
     * CurrentClubListener constructor.
     *
     * @param ClubManager $clubManager
     * @param RouterInterface $router
     * @param Logger $logger
     */
    public function __construct(ClubManager $clubManager, RouterInterface $router, Logger $logger)
    {
        $this->clubManager = $clubManager;
        $this->router = $router;
        $this->logger = $logger;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $this->requestUri = $request->getRequestUri();
        $club = $this->clubManager->getCurrentClub();
        if ($club === null) {
            $this->logger->info("Routename {$this->getRouteName()}");
            if ($this->requestUri === '/') {
                // Redirect if no subdomain and path is "/"
                $response = new RedirectResponse($this->router->generate('base_clubs'));
                $event->setResponse($response);
                // _profiler and _wdt is used for debugging in dev
            } elseif (!$this->isBaseRoute() && $this->getRouteName() !== '_profiler' && $this->getRouteName() !== '_wdt') {
                 // No subdomain and route is pointing to a code club page
                 throw new NotFoundHttpException();
             }
        } else {
            if ($this->isBaseRoute()) {
                // Remove subdomain if trying to go to a non-club page
                $fullUrl = $this->router->generate('base_clubs', array(), UrlGeneratorInterface::ABSOLUTE_URL);
                $this->logger->info("Removing subdomain from {$fullUrl}");
                $urlWithoutSubdomain = str_replace($club->getSubdomain().'.', '', $fullUrl);

                $response = new RedirectResponse($urlWithoutSubdomain);
                $event->setResponse($response);
            }
        }
    }

    private function isBaseRoute()
    {
        $routeName = $this->getRouteName();
        return strlen($routeName) > 5 && substr($routeName, 0, 5) === 'base_';
    }

    private function getRouteName()
    {
        try {
            return $this->router->match($this->requestUri)['_route'];
        } catch (ResourceNotFoundException $e) {
            // Ignore exception
        } catch (MethodNotAllowedException $e) {
            // Ignore exception, will be handled in the firewall
        }

        return '';
    }
}
