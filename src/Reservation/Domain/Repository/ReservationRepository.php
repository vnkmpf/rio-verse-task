<?php

declare(strict_types=1);

namespace App\Reservation\Domain\Repository;

use App\Event\Domain\Entity\Event;
use App\Reservation\Domain\Entity\Reservation;
use Symfony\Component\Uid\Uuid;

interface ReservationRepository
{
    public function store(Reservation $reservation): void;

    /** @return Reservation[] */
    public function getReservationsForEvent(Event $event): array;

    public function getById(Uuid $id): ?Reservation;

    public function delete(Reservation $reservation): void;
}
