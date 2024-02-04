<?php

declare(strict_types=1);

namespace App\Service\Domain\Entity;

use Symfony\Component\Uid\Uuid;

final class Service
{
    public function __construct(
        public Uuid $id,
        public string $name, // 128
        public int $duration, // (minutes)
        public int $capacity,
        public string $description,
        public int $cancellation_limit, // (minutes)
        public Uuid $staff,
    ) {
    }
}
