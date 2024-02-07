<?php

declare(strict_types=1);

namespace App\Reservation\Application\Controller;

use App\Reservation\Application\Service\ReservationService;
use App\Shared\Application\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Uid\Uuid;

#[Route('/reservation/{id}', methods: ['DELETE'], requirements: ['id' => Requirement::UUID_V7])]
final class DeleteReservation extends AbstractController
{
    public function __construct(
        private readonly ReservationService $reservation_service,
    ) {
    }

    public function __invoke(Uuid $id): Response
    {
        $this->reservation_service->deleteById($id);

        return new JsonResponse(status: 204);
    }
}
