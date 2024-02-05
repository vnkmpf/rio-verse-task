<?php

declare(strict_types=1);

namespace App\Event\Application\Controller;

use App\Event\Application\Service\EventService;
use App\Event\Domain\Entity\Event;
use App\Event\Domain\EventStatus;
use App\Shared\Application\Controller\AbstractController;
use App\Shared\DataType\DateImmutable;
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
        private readonly EventService $event_service,
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
            $this->event_service->store($event);
        } catch (\Exception $e) {
            return $this->sendJsonProblem($e);
        }

        return new JsonResponse($event, 201);
    }
}
