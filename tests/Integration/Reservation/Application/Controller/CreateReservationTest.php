<?php

declare(strict_types=1);

namespace App\Tests\Integration\Reservation\Application\Controller;

use App\Reservation\Domain\ReservationStatus;
use App\Tests\Integration\ApiTestCase;
use DataFixtures\Event\EventFixture;
use DataFixtures\User\StaffFixture;
use DataFixtures\User\UserFixture;

final class CreateReservationTest extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
    }

    public function testCreateReservationForAuthenticatedUser(): void
    {
        $event_id = EventFixture::EVENT_UUID;
        $response = $this->post('/reservations', auth_token: UserFixture::CAROL_TOKEN, content: <<< JSON
            {
                "event_id": "{$event_id}",
                "description": "desc"
            }
            JSON,
        );
        $data = $this->getResponseObject($response);

        static::assertStatusCode(201, $response);
        static::assertUuid($data->id);
        static::assertSame(UserFixture::CAROL_UUID, $data->user_id);
        static::assertSame($event_id, $data->event_id);
        static::assertSame('Carol Christmas', $data->user_name);
        static::assertSame('carol@example.com', $data->user_email);
        static::assertSame('desc', $data->description);
        static::assertSame(ReservationStatus::PENDING->value, $data->status);
    }

    public function testMissingDataForAuthenticatedUser(): void
    {
        $response = $this->post('/reservations', auth_token: UserFixture::CAROL_TOKEN, content: <<< JSON
            {
                "description": "desc"
            }
            JSON,
        );
        $data = $this->getResponseObject($response);

        static::assertStatusCode(400, $response);
        static::assertSame('Missing required data.', $data->title);
    }

    public function testUnauthenticatedUserCannotCreate(): void
    {
        $event_id = EventFixture::EVENT_UUID;
        $response = $this->post('/reservations', content: <<< JSON
            {
                "event_id": "{$event_id}",
                "description": "desc"
            }
            JSON,
        );

        static::assertStatusCode(401, $response);
    }

    public function testInvalidTokenAuthCannotCreate(): void
    {
        $event_id = EventFixture::EVENT_UUID;
        $response = $this->post('/reservations', auth_token: '0000', content: <<< JSON
            {
                "event_id": "{$event_id}",
                "description": "desc"
            }
            JSON,
        );

        static::assertStatusCode(401, $response);
    }

    public function testStaffCanCreateReservationForNonExistingUser(): void
    {
        $event_id = EventFixture::EVENT_UUID;
        $response = $this->post('/reservations', auth_token: StaffFixture::ALICE_TOKEN, content: <<< JSON
            {
                "user_email": "charlie@example.com",
                "user_name": "Charlie X.",
                "event_id": "{$event_id}",
                "description": "desc"
            }
            JSON,
        );
        $data = $this->getResponseObject($response);

        static::assertStatusCode(201, $response);
        static::assertUuid($data->id);
        static::assertNull($data->user_id);
        static::assertSame($event_id, $data->event_id);
        static::assertSame('Charlie X.', $data->user_name);
        static::assertSame('charlie@example.com', $data->user_email);
        static::assertSame('desc', $data->description);
        static::assertSame(ReservationStatus::PENDING->value, $data->status);
    }
}
