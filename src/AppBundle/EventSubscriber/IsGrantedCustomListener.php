<?php

namespace AppBundle\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use AppBundle\Security\User\AuthorizationChecker;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Guard\Token\GuardTokenInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerArgumentsEvent;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Handles the IsGranted annotation on controllers.
 *
 * @author Ryan Weaver <ryan@knpuniversity.com>
 */
class IsGrantedCustomListener implements EventSubscriberInterface
{
    private $authChecker;
    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage, AuthorizationChecker $authChecker = null)
    {
        $this->tokenStorage = $tokenStorage;
        $this->authChecker = $authChecker;
    }

    public function onKernelControllerArguments(FilterControllerArgumentsEvent $event)
    {
        $token = $this->tokenStorage->getToken();
        if (isset($token) && !$this->isTokenAuthenticated($token)) {
            return;
        }

        $request = $event->getRequest();
        /** @var $configurations IsGranted[] */
        if (!$configurations = $request->attributes->get('_is_granted')) {
            return;
        }

        if (null === $this->authChecker) {
            throw new \LogicException('To use the @IsGranted tag, you need to install symfony/security-bundle and configure your security system.');
        }

        foreach ($configurations as $configuration) {
            $this->authChecker->isGranted($configuration->getAttributes());
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [KernelEvents::CONTROLLER_ARGUMENTS => 'onKernelControllerArguments'];
    }

    private function isTokenAuthenticated(TokenInterface $token): bool
    {
        return $token instanceof GuardTokenInterface;
    }
}
