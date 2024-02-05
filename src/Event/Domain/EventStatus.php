<?php

declare(strict_types=1);

namespace App\Event\Domain;

enum EventStatus: string
{
    case ACTIVE = 'active';

    case CANCELED = 'canceled';
}
