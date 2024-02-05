<?php

declare(strict_types=1);

namespace App\Event\Domain\Entity;

use App\Event\Domain\EventStatus;
use App\Shared\DataType\DateImmutable;
use Symfony\Component\Uid\Uuid;

final class Event
{
    /**
     * @param int $start minutes since midnight
     * @param int $end minutes since midnight
     */
    public function __construct(
        public Uuid $id,
        public int $start,
        public int $end,
        public DateImmutable $date,
        public Uuid $service_id,
        public EventStatus $status,
    ) {
    }
}
