<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LoginErrorEventSubscriber implements EventSubscriberInterface
{
    private const PATH_LOGIN = '/api/login';

    public function onResponse(ResponseEvent $event): void
    {
        if ($this->supports($event)) {
            $event->setResponse(new JsonResponse(['message' => 'Bad credentials.'], Response::HTTP_UNAUTHORIZED));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => ['onResponse', 10],
        ];
    }

    private function supports(ResponseEvent $event): bool
    {
        $response = $event->getResponse();
        $request = $event->getRequest();

        return $response->getStatusCode() === Response::HTTP_UNAUTHORIZED
            && $request->getRequestUri() === self::PATH_LOGIN;
    }
}
