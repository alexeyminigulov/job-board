<?php

namespace AppBundle\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use AppBundle\Security\User\AuthorizationChecker;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Request\ArgumentNameConverter;
use Symfony\Component\HttpKernel\Event\FilterControllerArgumentsEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Handles the IsGranted annotation on controllers.
 *
 * @author Ryan Weaver <ryan@knpuniversity.com>
 */
class IsGrantedCustomListener implements EventSubscriberInterface
{
    private $authChecker;

    public function __construct(AuthorizationChecker $authChecker = null)
    {
        $this->authChecker = $authChecker;
    }

    public function onKernelControllerArguments(FilterControllerArgumentsEvent $event)
    {
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
}
