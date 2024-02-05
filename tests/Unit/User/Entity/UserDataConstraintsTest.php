<?php

declare(strict_types=1);

namespace App\Tests\Unit\User\Entity;

use App\User\Domain\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\UuidV7;

final class UserDataConstraintsTest extends TestCase
{
    /**
     * This rule ignores Unicode valid emails
     * @see https://en.wikipedia.org/wiki/International_email#Email_addresses
     *
     * This is an example solution, in reality more robust solution would be used
     */
    public function testStringDataIsNormalized(): void
    {
        $service = new User(
            new UuidV7(),
            "nam\u{0065}\u{0301}",
            "alice@example.com",
            // "alice@exampl\u{0065}\u{0301}.com",
            "tok\u{0065}\u{0301}n",
        );

        static::assertSame("nam\u{00e9}", $service->name);
        // static::assertSame("alice@exampl\u{00e9}.com", $service->email);
        static::assertSame("tok\u{00e9}n", $service->token);
    }

    public function testStringsAreTrimmed(): void
    {
        $service = new User(
            new UuidV7(),
            ' name ',
            ' alice@example.com ',
            ' token ',
        );

        static::assertSame('name', $service->name);
        static::assertSame('alice@example.com', $service->email);
        static::assertSame('token', $service->token);
    }

    public function testEmailIsValid(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new User(
            new UuidV7(),
            'Alice Doe',
            'email',
            'token',
        );
    }
}
