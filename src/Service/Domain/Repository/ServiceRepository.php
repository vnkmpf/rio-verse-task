<?php

declare(strict_types=1);

namespace App\Service\Domain\Repository;

use App\Service\Domain\Entity\Service;
use Symfony\Component\Uid\Uuid;

interface ServiceRepository
{
    public function findById(Uuid $id): ?Service;
}
