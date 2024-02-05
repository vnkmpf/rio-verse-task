<?php

declare(strict_types=1);

namespace App\User\Domain\Entity;

use Symfony\Component\Uid\Uuid;

final class User
{
    public function __construct(
        public Uuid $id,
        public string $name, //(128)
        public string $email, //(255)
        public string $token, //(128)
    ) {
        $this->name = \Normalizer::normalize(trim($this->name));
        $this->email = \Normalizer::normalize(trim($this->email));
        $this->token = \Normalizer::normalize(trim($this->token));

        if (false === filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('invalid email address');
        }
    }
}
