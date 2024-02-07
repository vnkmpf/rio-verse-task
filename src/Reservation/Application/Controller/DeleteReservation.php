<?php

declare(strict_types=1);

namespace App\Reservation\Application\Controller;

use App\Event\Application\Service\EventService;
use App\Reservation\Application\Service\ReservationService;
use App\Shared\Application\Controller\AbstractController;
use App\User\Domain\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV7;

#[Route('/reservation/{id}', methods: ['DELETE'], requirements: ['id' => Requirement::UUID_V7])]
final class DeleteReservation extends AbstractController
{
    public function __construct(
        private readonly ReservationService $reservation_service,
        private readonly EventService $event_service,
    ) {
    }

    public function __invoke(Request $request, Uuid $id): Response
    {
        try {
            $user_id = $request->headers->get('X-user-id');
            $reservation = $this->reservation_service->getById($id)
                ?? throw new BadRequestHttpException('event_id');
            $service = $this->event_service->getById($reservation->event_id)
                ?? throw new BadRequestHttpException('event_id');

            if (
                $user_id !== $service->staff_id->toRfc4122()
                && $user_id !== $reservation->user_id?->toRfc4122()
            ) {
                throw new NotFoundHttpException();
            }

            $this->reservation_service->deleteById($id);
        } catch (\Exception $e) {
            return $this->sendJsonProblem($e);
        }

        return new JsonResponse(status: 204);
    }
}
