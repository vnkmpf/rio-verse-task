<?php

declare(strict_types=1);

namespace App\Reservation\Domain\Repository;

use App\Reservation\Domain\Entity\Reservation;

interface ReservationRepository
{
    public function store(Reservation $reservation): void;

    /** @return Reservation[] */
    public function getReservationsForEvent(\App\Event\Domain\Entity\Event $event): array;
}
