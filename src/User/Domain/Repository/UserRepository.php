<?php

declare(strict_types=1);

namespace App\User\Domain\Repository;

use App\User\Domain\Entity\User;
use Symfony\Component\Uid\Uuid;

interface UserRepository
{
    public function getByToken(string $token): ?User;

    public function getById(Uuid $id): ?User;
}
