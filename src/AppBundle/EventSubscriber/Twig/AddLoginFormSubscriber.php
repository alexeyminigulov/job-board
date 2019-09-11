<?php

namespace AppBundle\EventSubscriber\Twig;

use AppBundle\Form\LoginForm;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class AddLoginFormSubscriber implements EventSubscriberInterface
{
    private $twig;
    private $router;
    private $formFactory;

    public function __construct(\Twig_Environment $twig, RouterInterface $router, FormFactoryInterface $formFactory)
    {
        $this->twig = $twig;
        $this->router = $router;
        $this->formFactory = $formFactory;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'registerCurrentController',
        ];
    }

    public function registerCurrentController(FilterControllerEvent $event): void
    {
        if ($event->getRequest()->getPathInfo() === $this->router->generate('security_login')) {
            return;
        }

        $form = $this->formFactory->create(LoginForm::class);

        $this->twig->addGlobal('form', $form->createView());
    }
}
