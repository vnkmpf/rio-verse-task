<?php

declare(strict_types=1);

namespace App\Reservation\Application\Controller;

use App\Reservation\Domain\Entity\Reservation;
use App\Reservation\Domain\ReservationStatus;
use App\Shared\Application\Controller\AbstractController;
use App\User\Domain\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\UuidV7;

#[Route('/reservations', methods: ['POST'])]
final class CreateReservation extends AbstractController
{
    public function __construct(
        private readonly UserRepository $user_repository,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        $user_id = $request->headers->get('X-user_id');
        $user = $this->user_repository->getById(new UuidV7($user_id));

        if ($user === null) {
            throw new UnauthorizedHttpException('');
        }

        try {
            $request_data = $this->decodeJsonContent($request);
            $reservation = new Reservation(
                new UuidV7(),
                $user->name,
                $user->email,
                $user->id,
                new UuidV7($request_data->event_id ?? throw new BadRequestHttpException('event_id')),
                $request_data->description ?? throw new BadRequestHttpException('description'),
                ReservationStatus::PENDING,
            );
            return new JsonResponse($reservation, 201);
        } catch (\Exception $e) {
            return $this->sendJsonProblem($e);
        }
    }
}
