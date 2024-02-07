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
        public Uuid $staff_id,
    ) {
        if ($this->start < 0) {
            throw new \InvalidArgumentException('Start cannot be negative');
        }

        if ($this->start >= 1440) {
            throw new \InvalidArgumentException('Start cannot be outside current date');
        }

        if ($this->end <= $this->start) {
            throw new \InvalidArgumentException('End cannot be before or at the same time as start');
        }

        if ($this->end >= 1440) {
            throw new \InvalidArgumentException('End cannot be outside current date');
        }
    }
}
