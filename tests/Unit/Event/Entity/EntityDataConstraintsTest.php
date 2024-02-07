<?php

declare(strict_types=1);

namespace App\Tests\Unit\Event\Entity;

use App\Event\Domain\Entity\Event;
use App\Event\Domain\EventStatus;
use App\Shared\DataType\DateImmutable;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV7;

final class EntityDataConstraintsTest extends TestCase
{
    public function testStartCannotBeNegative(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Event(
            new UuidV7(),
            -1,
            600,
            new DateImmutable('2020-01-31'),
            new UuidV7(),
            EventStatus::ACTIVE,
            new UuidV7(),
        );
    }

    #[DoesNotPerformAssertions]
    public function testStartCanBeAt00_00(): void
    {
        new Event(
            new UuidV7(),
            0,
            600,
            new DateImmutable('2020-01-31'),
            new UuidV7(),
            EventStatus::ACTIVE,
            new UuidV7(),
        );
    }

    public function testEndCannotBeBeforeStart(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Event(
            new UuidV7(),
            600,
            599,
            new DateImmutable('2020-01-31'),
            new UuidV7(),
            EventStatus::ACTIVE,
            new UuidV7(),
        );
    }

    public function testEndCannotBeAtTheSameTimeAsStart(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Event(
            new UuidV7(),
            600,
            600,
            new DateImmutable('2020-01-31'),
            new UuidV7(),
            EventStatus::ACTIVE,
            new UuidV7(),
        );
    }

    public function testStartCannotBeAt00_00NextDay(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Start cannot be outside current date');
        new Event(
            new UuidV7(),
            24 * 60,
            24 * 60 + 1,
            new DateImmutable('2020-01-31'),
            new UuidV7(),
            EventStatus::ACTIVE,
            new UuidV7(),
        );
    }

    public function testStartCannotBeAfterTheNextMidnight(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Start cannot be outside current date');
        new Event(
            new UuidV7(),
            24 * 60 + 1,
            24 * 60 + 2,
            new DateImmutable('2020-01-31'),
            new UuidV7(),
            EventStatus::ACTIVE,
            new UuidV7(),
        );
    }

    public function testEndCannotBeOutsideCurrentDay(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Event(
            new UuidV7(),
            23 * 60,
            24 * 60,
            new DateImmutable('2020-01-31'),
            new UuidV7(),
            EventStatus::ACTIVE,
            new UuidV7(),
        );
    }
}
