<?php

declare(strict_types=1);

namespace App\Reservation\Domain\Entity;

use App\Reservation\Domain\ReservationStatus;
use Symfony\Component\Uid\Uuid;

final class Reservation
{
    public function __construct(
        public Uuid $id,
        public string $user_name,
        public string $user_email,
        public ?Uuid $user_id,
        public Uuid $event_id,
        public string $description,
        public ReservationStatus $status,
    ) {
        $this->user_name = \Normalizer::normalize(trim($this->user_name), \Normalizer::NFC);
        $this->user_email = \Normalizer::normalize(trim($this->user_email), \Normalizer::NFC);
        $this->description = \Normalizer::normalize(trim($this->description), \Normalizer::NFC);

        if ($this->user_name === '') {
            throw new \InvalidArgumentException('User name cannot be empty');
        }

        if (false === filter_var($this->user_email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email address');
        }
    }
}
