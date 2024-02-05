<?php

declare(strict_types=1);

namespace App\Tests\Integration\Event\Application\Controller;

use DataFixtures\Service\ServiceFixture;
use DataFixtures\User\StaffFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CreateEventTest extends WebTestCase
{
    public function testCanCreateEvent(): void
    {
        $service_uuid = ServiceFixture::SERVICE_UUID;
        $client = static::createClient(server: [
            'HTTP_AUTHORIZATION' => 'token ' . StaffFixture::STAFF_TOKEN,
        ]);
        $client->request('POST', '/events', content: <<< JSON
            {
                "start": 480,
                "end": 540,
                "date": "2100-12-31",
                "service_id": "{$service_uuid}"
            }
            JSON
        );
        $response = $client->getResponse();
        $created_service = json_decode($response->getContent(), false, 512, JSON_THROW_ON_ERROR);

        static::assertSame(201, $response->getStatusCode());
        static::assertSame(480, $created_service->start);
        static::assertSame(540, $created_service->end);
        static::assertSame('2100-12-31', $created_service->date);
        static::assertSame($service_uuid, $created_service->service_id);
        static::assertSame('active', $created_service->status);
        static::assertUuid($created_service->id);
    }

    public function testTryingToCreateEventWithoutData(): void
    {
        $client = static::createClient(server: [
            'HTTP_AUTHORIZATION' => 'token ' . StaffFixture::STAFF_TOKEN,
        ]);
        $client->request('POST', '/events');
        $response = $client->getResponse();
        $created_service = json_decode($response->getContent(), false, 512, JSON_THROW_ON_ERROR);

        static::assertSame(400, $response->getStatusCode());
    }

    public function testTryingToCreateEventWithMissingData(): void
    {
        $client = static::createClient(server: [
            'HTTP_AUTHORIZATION' => 'token ' . StaffFixture::STAFF_TOKEN,
        ]);
        $client->request('POST', '/events', content: <<< JSON
            {
                "start": 480,
                "end": 540,
                "date": "2000-12-31"
            }
            JSON
        );
        $response = $client->getResponse();
        $response_content = json_decode($response->getContent(), false, 512, JSON_THROW_ON_ERROR);

        static::assertSame(400, $response->getStatusCode());
        static::assertSame('Missing required data.', $response_content->title);
    }

    public function testInvalidDataConstraint(): void
    {
        $service_uuid = ServiceFixture::SERVICE_UUID;
        $client = static::createClient(server: [
            'HTTP_AUTHORIZATION' => 'token ' . StaffFixture::STAFF_TOKEN,
        ]);
        $client->request('POST', '/events', content: <<< JSON
            {
                "start": -480,
                "end": 540,
                "date": "2000-12-31",
                "service_id": "{$service_uuid}"
            }
            JSON
        );
        $response = $client->getResponse();
        $response_content = json_decode($response->getContent(), false, 512, JSON_THROW_ON_ERROR);

        static::assertSame(400, $response->getStatusCode());
        static::assertSame('Data constraint problem.', $response_content->title);
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
