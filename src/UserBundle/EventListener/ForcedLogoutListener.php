<?php

namespace UserBundle\EventListener;

use CodeClubBundle\Service\ClubManager;
use Monolog\Logger;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use UserBundle\Entity\User;


class ForcedLogoutListener
{
    /** @var  TokenStorageInterface */
    protected $tokenStorage;

    /** @var  AuthorizationCheckerInterface */
    protected $authChecker;

    /** @var  SessionInterface */
    protected $session;

    /** @var  RouterInterface */
    protected $router;

    /** @var  string */
    protected $sessionName;

    /** @var  string */
    protected $rememberMeSessionName;

    /** @var ClubManager */
    private $clubManager;
    /**
     * @var Logger
     */
    private $logger;


    /**
     * @param TokenStorageInterface $tokenStorage
     * @param AuthorizationCheckerInterface $authChecker
     * @param SessionInterface $session
     * @param RouterInterface $router
     * @param ClubManager $clubManager
     * @param Logger $logger
     * @param string $sessionName
     * @param string $rememberMeSessionName
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $authChecker,
        SessionInterface $session,
        RouterInterface $router,
        ClubManager $clubManager,
        Logger $logger,
        $sessionName,
        $rememberMeSessionName
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->authChecker = $authChecker;
        $this->session = $session;
        $this->router = $router;
        $this->clubManager = $clubManager;
        $this->sessionName = $sessionName;
        $this->rememberMeSessionName = $rememberMeSessionName;
        $this->logger = $logger;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest() || !$this->isUserLoggedIn()) {
            return;
        }

        $accessToken = $this->tokenStorage->getToken();

        /** @var User $user */
        $user = $accessToken->getUser();

        // Forcing user to log out if required.
        if ($user->getClub() !== $this->clubManager->getCurrentClub()) {
            $this->logger->info('Force logging out '.$user);

            // Logging user out.
            $response = $this->getRedirectResponse('security_login_form');
            $this->logUserOut($response);


            // Setting redirect response.
            $event->setResponse($response);
        }
    }

    protected function isUserLoggedIn()
    {
        try {
            return $this->authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED');
        } catch (AuthenticationCredentialsNotFoundException $exception) {
            // Ignoring this exception.
        }

        return false;
    }

    /**
     * @param string $routeName
     *
     * @return RedirectResponse
     */
    protected function getRedirectResponse($routeName)
    {
        return new RedirectResponse(
            $this->router->generate($routeName)
        );
    }

    /**
     * @param Response $response
     */
    protected function logUserOut(Response $response = null)
    {
        // Logging user out.
        $this->tokenStorage->setToken(null);

        // Invalidating the session.
        $this->session->invalidate();

        // Clearing the cookies.
        if (null !== $response) {
            foreach ([
                         $this->sessionName,
                         $this->rememberMeSessionName,
                     ] as $cookieName) {
                $response->headers->clearCookie($cookieName);
            }
        }
    }
}
