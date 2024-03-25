<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final class ApiExceptionListener
{
    #[AsEventListener(event: ExceptionEvent::class)]
    public function onApiException(ExceptionEvent $event): void
    {
        if ($event->getThrowable() instanceof \ApiException) {
            $event->setResponse(new JsonResponse(['message' => 'Invalid json!'], Response::HTTP_BAD_REQUEST));
        }
    }
}
