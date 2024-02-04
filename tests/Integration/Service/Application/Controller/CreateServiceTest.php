<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service\Application\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CreateServiceTest extends WebTestCase
{
    public function testCanCreateService(): void
    {
        $client = static::createClient();
        $client->request('POST', '/services', content: <<< JSON
            {
                "cancellation_limit": 1440,
                "capacity": 10,
                "description": "Test",
                "duration": 60,
                "name": "Scuba diving"
            }
            JSON
        );
        $response = $client->getResponse();
        $created_service = json_decode($response->getContent(), false, 512, JSON_THROW_ON_ERROR);

        static::assertSame(201, $response->getStatusCode());
        static::assertSame(1440, $created_service->cancellation_limit);
        static::assertSame(10, $created_service->capacity);
        static::assertSame('Test', $created_service->description);
        static::assertSame(60, $created_service->duration);
        static::assertSame('Scuba diving', $created_service->name);
        static::assertUuid($created_service->id);
    }

    private static function assertUuid(string $var): void
    {
        static::assertMatchesRegularExpression(
            '#^[\\da-f]{8}-[\\da-f]{4}-[\\da-f]{4}-[\\da-f]{4}-[\\da-f]{12}$#i',
            $var,
            "Failed asserting that {$var} is a UUID",
        );
    }
}
