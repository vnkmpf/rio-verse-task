<?php

declare(strict_types=1);

namespace App\Reservation\Domain;

enum ReservationStatus: string
{
    case PENDING = 'pending';

    case CANCELED = 'canceled';

    case CONFIRMED = 'confirmed';
}
