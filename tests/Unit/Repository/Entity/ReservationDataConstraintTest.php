<?php

declare(strict_types=1);

namespace App\Tests\Unit\Repository\Entity;

use App\Reservation\Domain\Entity\Reservation;
use App\Reservation\Domain\ReservationStatus;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV7;

final class ReservationDataConstraintTest extends TestCase
{
    public function testStringsAreNormalized(): void
    {
        $reservation = new Reservation(
            new UuidV7(),
            "John Do\u{0065}\u{0301}",
            'john@example.com',
            new UuidV7(),
            new UuidV7(),
            "d\u{0065}\u{0301}scription",
            ReservationStatus::PENDING,
        );

        static::assertSame("John Do\u{00e9}", $reservation->user_name);
        static::assertSame("d\u{00e9}scription", $reservation->description);
    }

    public function testStringsAreTrimmed(): void
    {
        $reservation = new Reservation(
            new UuidV7(),
            ' John Doe ',
            ' john@example.com ',
            new UuidV7(),
            new UuidV7(),
            ' description ',
            ReservationStatus::PENDING,
        );

        static::assertSame('John Doe', $reservation->user_name);
        static::assertSame('john@example.com', $reservation->user_email);
        static::assertSame('description', $reservation->description);
    }

    public function testEmailIsValid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Reservation(
            new UuidV7(),
            'John Doe',
            'john',
            new UuidV7(),
            new UuidV7(),
            'description',
            ReservationStatus::PENDING,
        );
    }

    public function testNameIsNotEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Reservation(
            new UuidV7(),
            '',
            'john@example.com',
            new UuidV7(),
            new UuidV7(),
            'description',
            ReservationStatus::PENDING,
        );
    }
}
