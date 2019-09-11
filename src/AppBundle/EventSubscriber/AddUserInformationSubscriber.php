<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Entity\User\User;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AddUserInformationSubscriber implements EventSubscriberInterface
{
    private $twig;
    private $tokenStorage;

    public function __construct(\Twig_Environment $twig, TokenStorageInterface $tokenStorage)
    {
        $this->twig = $twig;
        $this->tokenStorage = $tokenStorage;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'addGlobalUserInformation',
        ];
    }

    public function addGlobalUserInformation(GetResponseEvent $event): void
    {
        $user = $this->getUser();

        if ($user && $event->isMasterRequest()) {
            $this->twig->addGlobal('user', $user);
        }
    }

    private function getUser(): ?User
    {
        if ($token = $this->tokenStorage->getToken()) {
            if ($user = $token->getUser()) {
                return $user;
            }
        }

        return null;
    }
}
