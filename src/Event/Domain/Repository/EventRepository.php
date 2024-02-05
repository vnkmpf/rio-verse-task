<?php

declare(strict_types=1);

namespace App\Event\Domain\Repository;

use App\Event\Domain\Entity\Event;
use Symfony\Component\Uid\Uuid;

interface EventRepository
{
    public function getById(Uuid $id): ?Event;
}
