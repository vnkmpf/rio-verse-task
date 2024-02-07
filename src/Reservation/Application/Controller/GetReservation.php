<?php

declare(strict_types=1);

namespace App\Reservation\Application\Controller;

use App\Reservation\Application\Service\ReservationService;
use App\Shared\Application\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Uid\Uuid;

#[Route('/reservation/{id}', methods: ['GET'], requirements: ['id' => Requirement::UUID_V7])]
final class GetReservation extends AbstractController
{
    public function __construct(
        private readonly ReservationService $reservation_service,
    ) {
    }

    public function __invoke(Uuid $id): Response
    {
        $reservation = $this->reservation_service->getById($id);

        try {
            if ($reservation === null) {
                throw new NotFoundHttpException();
            }
        } catch (\Exception $e) {
            return $this->sendJsonProblem($e);
        }

        return new JsonResponse($reservation, 200);
    }
}
