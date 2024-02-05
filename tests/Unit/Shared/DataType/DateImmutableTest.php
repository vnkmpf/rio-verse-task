<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\DataType;

use App\Shared\DataType\DateImmutable;
use PHPUnit\Framework\TestCase;

final class DateImmutableTest extends TestCase
{
    public function testDateIsCreatedFromString(): void
    {
        static::assertSame(
            '2023-12-24',
            (string) new DateImmutable('2023-12-24'),
        );
    }

    public function testInvalidDateErrs(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new DateImmutable('2023-12-32');
    }

    public function testCanBeJsonSerialized(): void
    {
        static::assertSame(
            json_encode('2023-10-28'),
            json_encode(new DateImmutable('2023-10-28')),
        );
    }
}
