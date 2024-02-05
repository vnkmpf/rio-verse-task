<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Entity;

use App\Service\Domain\Entity\Service;
use PHPUnit\Framework\Attributes\DoesNotPerformAssertions;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV7;

final class ServiceDataConstraintsTest extends TestCase
{
    public function testCancellationCannotBeNegative(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Service(
            $this->getUuid(),
            'name',
            1,
            1,
            'description',
            -1,
            $this->getUuid(),
        );
    }

    #[DoesNotPerformAssertions]
    public function testCancellationCanBeZero(): void
    {
        new Service(
            $this->getUuid(),
            'name',
            1,
            1,
            'description',
            0,
            $this->getUuid(),
        );
    }

    public function testCapacityCannotBeNegative(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Service(
            $this->getUuid(),
            'name',
            1,
            -1,
            'description',
            1,
            $this->getUuid(),
        );
    }

    public function testCapacityCannotBeZero(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Service(
            $this->getUuid(),
            'name',
            1,
            0,
            'description',
            1,
            $this->getUuid(),
        );
    }

    public function testNameCannotBeEmpty(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Service(
            $this->getUuid(),
            '',
            1,
            1,
            'description',
            1,
            $this->getUuid(),
        );
    }

    public function testDurationCannotBeNegative(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Service(
            $this->getUuid(),
            'name',
            -1,
            1,
            'description',
            1,
            $this->getUuid(),
        );
    }

    public function testDurationCannotBeZero(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Service(
            $this->getUuid(),
            'name',
            0,
            1,
            'description',
            1,
            $this->getUuid(),
        );
    }

    public function testStringDataIsNormalized(): void
    {
        $service = new Service(
            $this->getUuid(),
            "nam\u{0065}\u{0301}",
            10,
            1,
            "d\u{0065}\u{0301}scription",
            1,
            $this->getUuid(),
        );

        static::assertSame("nam\u{00e9}", $service->name);
        static::assertSame("d\u{00e9}scription", $service->description);
    }

    private function getUuid(): Uuid
    {
        return new UuidV7();
    }
}
