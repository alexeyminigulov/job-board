<?php

namespace AppBundle\EventSubscriber;

use AppBundle\Form\SearchForm;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class AddSearchFormSubscriber implements EventSubscriberInterface
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
        if ($event->getRequest()->getPathInfo() === $this->router->generate('search')) {
            return;
        }

        $form = $this->formFactory->create(SearchForm::class, null, [
            'action' => $this->router->generate('search'),
            'method' => 'GET',
            'csrf_protection' => false,
        ]);

        $this->twig->addGlobal('formSearch', $form->createView());
    }
}
