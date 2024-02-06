<?php

declare(strict_types=1);

namespace App\Shared\Application\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

abstract class AbstractController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws \JsonException
     */
    protected function decodeJsonContent(Request $request): object
    {
        return (object) json_decode(
            $request->getContent(),
            false,
            512,
            JSON_THROW_ON_ERROR,
        );
    }

    protected function sendJsonProblem(\Exception $exception): JsonResponse
    {
        return match (true) {
            $exception instanceof \JsonException => new JsonResponse([
                'type' => 'https://example.com/probs/wrong-data',
                'title' => 'Invalid data format.',
                'detail' => 'Request data is not valid JSON.',
            ], 400, ['Content-Type' => 'application/problem+json']),
            $exception instanceof BadRequestHttpException => new JsonResponse([
                'type' => 'https://example.com/probs/missing-data',
                'title' => 'Missing required data.',
                'detail' => sprintf('Missing data "%s".', $exception->getMessage()),
            ], 400, ['Content-Type' => 'application/problem+json']),
            $exception instanceof \InvalidArgumentException => new JsonResponse([
                'type' => 'https://example.com/probs/wrong-data',
                'title' => 'Data constraint problem.',
                'detail' => $exception->getMessage(),
            ], 400, ['Content-Type' => 'application/problem+json']),
            $exception instanceof UnauthorizedHttpException => new JsonResponse([
                'type' => 'https://example.com/probs/unauthorized',
                'title' => 'Unauthorized request.',
                'detail' => 'Request not authorized.',
            ], 401, ['Content-Type' => 'application/problem+json']),
            $exception instanceof NotFoundHttpException => new JsonResponse([
                'type' => 'https://example.com/probs/not-found',
                'title' => 'Not found.',
                'detail' => 'Resource not found.',
            ], 404, ['Content-Type' => 'application/problem+json']),
            default => new JsonResponse([
                'type' => 'https://example.com/probs/whoops',
                'title' => 'Something went wrong.',
                'detail' => 'Something went wrong.',
            ], 500, ['Content-Type' => 'application/problem+json']),
        };
    }
}
