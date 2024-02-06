<?php

declare(strict_types=1);

namespace App\Tests\Integration\Event\Application\Controller;

use App\Tests\Integration\ApiTestCase;
use DataFixtures\Service\ServiceFixture;
use DataFixtures\User\StaffFixture;

final class CreateEventTest extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient(server: [
            'HTTP_AUTHORIZATION' => 'token ' . StaffFixture::ALICE_TOKEN,
        ]);
    }

    public function testCanCreateEvent(): void
    {
        $service_uuid = ServiceFixture::SPANISH_101_UUID;
        $response = $this->post('/events', <<< JSON
            {
                "start": 480,
                "end": 540,
                "date": "2100-12-31",
                "service_id": "{$service_uuid}"
            }
            JSON
        );
        $created_service = $this->getResponseObject($response);

        static::assertStatusCode(201, $response);
        static::assertSame(480, $created_service->start);
        static::assertSame(540, $created_service->end);
        static::assertSame('2100-12-31', $created_service->date);
        static::assertSame($service_uuid, $created_service->service_id);
        static::assertSame('active', $created_service->status);
        static::assertUuid($created_service->id);
    }

    public function testTryingToCreateEventWithoutData(): void
    {
        $response = $this->post('/events');
        static::assertStatusCode(400, $response);
    }

    public function testTryingToCreateEventWithMissingData(): void
    {
        $response = $this->post('/events', <<< JSON
            {
                "start": 480,
                "end": 540,
                "date": "2000-12-31"
            }
            JSON
        );
        $response_content = $this->getResponseObject($response);

        static::assertStatusCode(400, $response);
        static::assertSame('Missing required data.', $response_content->title);
    }

    public function testInvalidDataConstraint(): void
    {
        $service_uuid = ServiceFixture::SPANISH_101_UUID;
        $response = $this->post('/events', <<< JSON
            {
                "start": -480,
                "end": 540,
                "date": "2000-12-31",
                "service_id": "{$service_uuid}"
            }
            JSON
        );
        $response_content = $this->getResponseObject($response);

        static::assertStatusCode(400, $response);
        static::assertSame('Data constraint problem.', $response_content->title);
    }

    /**
     * From the point of view of specific user,
     * the other user's service does not exist.
     *
     * If several staff roles where created, and they didn't
     * have permission, 403 would be returned.
     */
    public function testCannotCreateEventForSomeoneElsesService(): void
    {
        $service_uuid = ServiceFixture::GEOLOGY_BASISC_UUID;
        $response = $this->post('/events', <<< JSON
            {
                "start": 480,
                "end": 540,
                "date": "2100-12-31",
                "service_id": "{$service_uuid}"
            }
            JSON
        );

        static::assertStatusCode(404, $response);
    }
}
