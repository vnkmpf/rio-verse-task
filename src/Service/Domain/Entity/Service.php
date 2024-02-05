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
        $this->name = \Normalizer::normalize($this->name, \Normalizer::NFC);
        $this->description = \Normalizer::normalize($this->description, \Normalizer::NFC);

        if ($this->name === '') {
            throw new \InvalidArgumentException('Name cannot be empty');
        }

        if ($this->cancellation_limit < 0) {
            throw new \InvalidArgumentException('Cancellation limit cannot be negative');
        }

        if ($this->capacity < 1) {
            throw new \InvalidArgumentException('Capacity has to be positive');
        }

        if ($this->duration < 1) {
            throw new \InvalidArgumentException('Duration has to be positive');
        }
    }
}
