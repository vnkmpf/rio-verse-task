<?php

namespace App\Shared\Infrastructure;

interface TimeProvider
{
    public function now(): \DateTimeImmutable;
}
