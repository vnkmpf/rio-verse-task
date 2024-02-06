<?php

declare(strict_types=1);

namespace App\Event\Application\Controller;

use App\Event\Application\Service\EventService;
use App\Service\Domain\Repository\ServiceRepository;
use App\Shared\Application\Controller\AbstractController;
use App\Shared\Infrastructure\Repository\EntityNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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
        private readonly ServiceRepository $service_repository,
    ) {
    }

    public function __invoke(Request $request, Uuid $id): Response
    {
        $user_id = $request->headers->get('x-user-id');
        $event = $this->event_service->getById($id) ?? throw new NotFoundHttpException();
        $service = $this->service_repository->findById($event->service_id);

        if ($service?->staff->toRfc4122() !== $user_id) {
            throw new NotFoundHttpException();
        }

        try {
            $this->event_service->deleteById($id);
        } catch (EntityNotFoundException $e) {
            throw new NotFoundHttpException(previous: $e);
        }
        return new JsonResponse(status: 204);
    }
}
