<?php

declare(strict_types=1);

namespace App\Reservation\Application\Service;

use App\Event\Application\Service\EventService;
use App\Event\Domain\Entity\Event;
use App\Event\Domain\EventStatus;
use App\Reservation\Domain\Entity\Reservation;
use App\Reservation\Domain\Exception\CannotCreateReservationException;
use App\Reservation\Domain\Repository\ReservationRepository;
use App\Shared\Infrastructure\Repository\EntityNotFoundException;

final class ReservationService
{
    public function __construct(
        private readonly ReservationRepository $reservation_repository,
        private readonly EventService $event_service,
    ) {
    }

    /**
     * @throws CannotCreateReservationException
     */
    public function tryToBook(Reservation $reservation, Event $event): Reservation
    {
        if ($event->status === EventStatus::CANCELED) {
            throw new CannotCreateReservationException('Event is canceled.');
        }

        $reservation_count = count($this->reservation_repository->getReservationsForEvent($event));

        try {
            $event_slot_count = $this->event_service->getAvailableSlotsCount($event);
        } catch (EntityNotFoundException $e) {
            throw new CannotCreateReservationException($e->getMessage(), previous: $e);
        }

        if ($event_slot_count <= $reservation_count) {
            throw new CannotCreateReservationException('Event is fully reserved.');
        }

        $this->reservation_repository->store($reservation);

        return $reservation;
    }
}
