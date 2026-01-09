<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Bundle\SecurityBundle\Security;

final class BannedUserSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly Security $security,
        private readonly RouterInterface $router,
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 8],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        if (null === $this->security->getUser()) {
            return;
        }

        if (!$this->security->isGranted('ROLE_BANNED')) {
            return;
        }

        $request = $event->getRequest();

        $route = (string) $request->attributes->get('_route', '');
        $path = $request->getPathInfo();

        // Always allow the banned landing page + logout.
        if ($route === 'app_banned' || $route === 'app_logout') {
            return;
        }

        // Allow Symfony technical routes/assets (profiler, wdt, etc.).
        if (str_starts_with($path, '/_')) {
            return;
        }

        $event->setResponse(new RedirectResponse($this->router->generate('app_banned')));
    }
}
