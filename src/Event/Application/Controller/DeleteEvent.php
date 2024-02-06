<?php

declare(strict_types=1);

namespace App\Event\Application\Controller;

use App\Event\Application\Service\EventService;
use App\Shared\Application\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Uid\Uuid;

#[Route('/event/{id}', methods: ['DELETE'], requirements: ['id' => Requirement::UUID_V7])]
final class DeleteEvent extends AbstractController
{
    public function __construct(
        private readonly EventService $event_service,
    ) {
    }

    public function __invoke(Uuid $id): Response
    {
        $event = $this->event_service->getById($id);

        if ($event === null) {
            throw new NotFoundHttpException();
        }

        $this->event_service->delete($event);
        return new JsonResponse(status: 204);
    }
}
