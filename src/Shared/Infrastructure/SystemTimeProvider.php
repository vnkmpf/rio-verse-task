<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure;

final class SystemTimeProvider implements TimeProvider
{
    #[\Override] public function now(): \DateTimeImmutable
    {
        return new \DateTimeImmutable('now');
    }
}
