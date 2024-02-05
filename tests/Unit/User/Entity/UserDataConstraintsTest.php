<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Entity;

use App\User\Domain\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV7;

final class UserDataConstraintsTest extends TestCase
{
    public function testStringDataIsNormalized(): void
    {
        $service = new User(
            new UuidV7(),
            "nam\u{0065}\u{0301}",
            "\u{0065}\u{0301}mail",
            "tok\u{0065}\u{0301}n",
        );

        static::assertSame("nam\u{00e9}", $service->name);
        static::assertSame("\u{00e9}mail", $service->email);
        static::assertSame("tok\u{00e9}n", $service->token);
    }
}
