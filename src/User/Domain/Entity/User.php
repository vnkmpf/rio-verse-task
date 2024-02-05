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
        $this->name = \Normalizer::normalize($this->name);
        $this->email = \Normalizer::normalize($this->email);
        $this->token = \Normalizer::normalize($this->token);
    }
}
