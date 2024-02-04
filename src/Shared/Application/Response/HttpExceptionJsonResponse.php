<?php

declare(strict_types=1);

namespace App\Shared\Application\Response;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

#[AsEventListener(event: 'kernel.exception')]
final class HttpExceptionJsonResponse
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof HttpException) {
            $event->setResponse(new JsonResponse(
                $this->getBody($exception),
                $exception->getStatusCode(),
                $this->getHeaders($exception),
            ));
        }
    }

    private function getBody(HttpException $exception): mixed
    {
        return match (true) {
            $exception instanceof UnauthorizedHttpException => [
                'type' => 'https://example.com/probs/unauthorized',
                'title' => 'Unauthorized request.',
                'detail' => 'Request not authorized.',
            ],
            default => $exception->getMessage(),
        };
    }

    /**
     * @return array<string, string>
     */
    private function getHeaders(HttpException $exception): array
    {
        return match (true) {
            $exception instanceof UnauthorizedHttpException => [
                'Content-Type' => 'application/problem+json',
            ],
            default => [],
        };
    }
}
