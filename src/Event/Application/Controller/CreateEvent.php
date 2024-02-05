<?php

declare(strict_types=1);

namespace App\Event\Application\Controller;

use App\Event\Domain\Entity\Event;
use App\Event\Domain\EventStatus;
use App\Event\Domain\Repository\EventRepository;
use App\Shared\DataType\DateImmutable;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\UuidV7;

#[Route('/events', methods: ['POST'])]
final class CreateEvent extends AbstractController
{
    public function __construct(
        private readonly EventRepository $event_repository,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        try {
            $post_data = $this->decodeJsonContent($request);
            $event = new Event(
                new UuidV7(),
                $post_data->start ?? throw new BadRequestHttpException('start'),
                $post_data->end ?? throw new BadRequestHttpException('end'),
                new DateImmutable($post_data->date ?? throw new BadRequestHttpException('date')),
                new UuidV7($post_data->service_id ?? throw new BadRequestHttpException('service_id')),
                EventStatus::ACTIVE,
            );
            $this->event_repository->store($event);
        } catch (\Exception $e) {
            return $this->sendJsonProblem($e);
        }

        return new JsonResponse($event, 201);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws \JsonException
     */
    private function decodeJsonContent(Request $request): object
    {
        return (object) json_decode(
            $request->getContent(),
            false,
            512,
            JSON_THROW_ON_ERROR,
        );
    }

    private function sendJsonProblem(\Exception $exception): JsonResponse
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
            default => new JsonResponse([
                'type' => 'https://example.com/probs/whoops',
                'title' => 'Something went wrong.',
                'detail' => 'Something went wrong.',
            ], 500, ['Content-Type' => 'application/problem+json']),
        };
    }
}
