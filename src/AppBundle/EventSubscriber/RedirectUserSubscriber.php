<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\User\User;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RedirectUserSubscriber implements EventSubscriberInterface
{
    private $tokenStorage;
    private $router;

    public function __construct(TokenStorageInterface $tokenStorage, RouterInterface $router)
    {
        $this->tokenStorage = $tokenStorage;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [KernelEvents::REQUEST => 'onKernelControllerArguments'];
    }

    public function onKernelControllerArguments(GetResponseEvent $event)
    {
        $currentRoute = $event->getRequest()->attributes->get('_route');
        if ($this->isAuthenticatedUserOnAnonymousPage($currentRoute)) {
            if ($this->isUserLogged() && $event->isMasterRequest()) {
                $response = new RedirectResponse($this->router->generate('homepage'));
                $event->setResponse($response);
            }
        }
    }

    private function isUserLogged()
    {
        if ($token = $this->tokenStorage->getToken()) {
            if ($user = $token->getUser()) {
                return $user instanceof User;
            }
        }

        return false;
    }

    private function isAuthenticatedUserOnAnonymousPage($currentRoute)
    {
        return in_array(
            $currentRoute,
            ['employer_signup', 'employee_signup']
        );
    }
}