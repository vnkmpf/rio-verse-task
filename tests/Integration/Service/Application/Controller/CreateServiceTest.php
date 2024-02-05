<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service\Application\Controller;

use DataFixtures\User\StaffFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CreateServiceTest extends WebTestCase
{
    public function testCanCreateService(): void
    {
        $client = static::createClient(server: [
            'HTTP_AUTHORIZATION' => 'token ' . StaffFixture::STAFF_TOKEN,
        ]);
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

    public function testNonJsonDataSent(): void
    {
        $client = static::createClient(server: [
            'HTTP_AUTHORIZATION' => 'token ' . StaffFixture::STAFF_TOKEN,
        ]);
        $client->request('POST', '/services', content: <<< TEXT
            not-json-data
            TEXT
        );

        $response = $client->getResponse();
        $response_data = json_decode($response->getContent(), false, 512, JSON_THROW_ON_ERROR);

        static::assertSame(400, $response->getStatusCode());
        static::assertSame('application/problem+json', $response->headers->get('Content-Type'));
        static::assertSame('Request data is not valid JSON.', $response_data->detail);
    }

    public function testMissingRequiredData(): void
    {
        $client = static::createClient(server: [
            'HTTP_AUTHORIZATION' => 'token ' . StaffFixture::STAFF_TOKEN,
        ]);
        $client->request('POST', '/services', content: <<< JSON
            {
                "cancellation_limit": 1440,
                "capacity": 10,
                "description": "Test",
                "duration": 60
            }
            JSON
        );

        $response = $client->getResponse();
        $response_data = json_decode($response->getContent(), false, 512, JSON_THROW_ON_ERROR);

        static::assertSame(400, $response->getStatusCode());
        static::assertSame('application/problem+json', $response->headers->get('Content-Type'));
        static::assertSame('Missing data "name".', $response_data->detail);
    }

    public function testDataConstraintsReturnBadData(): void
    {
        $client = static::createClient(server: [
            'HTTP_AUTHORIZATION' => 'token ' . StaffFixture::STAFF_TOKEN,
        ]);
        $client->request('POST', '/services', content: <<< JSON
            {
                "cancellation_limit": 1440,
                "capacity": -10,
                "description": "Test",
                "duration": 60,
                "name": "Scuba diving"
            }
            JSON
        );

        $response = $client->getResponse();
        $response_data = json_decode($response->getContent(), false, 512, JSON_THROW_ON_ERROR);

        static::assertSame(400, $response->getStatusCode());
        static::assertSame('application/problem+json', $response->headers->get('Content-Type'));
        static::assertSame('Capacity has to be positive', $response_data->detail);
    }

    public function testServiceIsCreatedWithMyUuidAsStaffId(): void
    {
        $client = static::createClient([], [
            'HTTP_AUTHORIZATION' => 'token ' . StaffFixture::STAFF_TOKEN,
        ]);
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
        $response_data = json_decode($response->getContent(), false, 512, JSON_THROW_ON_ERROR);

        static::assertSame(StaffFixture::STAFF_UUID, $response_data->staff);
    }

    public function testUnauthorizedRequestErrs(): void
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
        $response_data = json_decode($response->getContent(), false, 512, JSON_THROW_ON_ERROR);

        static::assertSame(401, $response->getStatusCode());
        static::assertSame('application/problem+json', $response->headers->get('Content-Type'));
        static::assertSame('Request not authorized.', $response_data->detail);
    }

    public function testUserWithTokenDoesNotExist(): void
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
            JSON, server: [
                'HTTP_AUTHORIZATION' => 'token 0000000000000000',
            ],
        );

        $response = $client->getResponse();
        $response_data = json_decode($response->getContent(), false, 512, JSON_THROW_ON_ERROR);

        static::assertSame(401, $response->getStatusCode());
        static::assertSame('application/problem+json', $response->headers->get('Content-Type'));
        static::assertSame('Request not authorized.', $response_data->detail);
    }

    public function testStringsAreUnicodeNormalized(): void
    {
        $client = static::createClient(server: [
            'HTTP_AUTHORIZATION' => 'token ' . StaffFixture::STAFF_TOKEN,
        ]);
        $client->request('POST', '/services', content: <<<JSON
            {
                "cancellation_limit": 1440,
                "capacity": 10,
                "description": "T\\u0065\\u0301st",
                "duration": 60,
                "name": "Scuba diving \\u0065\\u0301"
            }
            JSON,
        );
        $response = $client->getResponse();
        $created_service = json_decode($response->getContent(), false, 512, JSON_THROW_ON_ERROR);

        static::assertSame("T\u{00e9}st", $created_service->description);
        static::assertSame("Scuba diving \u{00e9}", $created_service->name);
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
