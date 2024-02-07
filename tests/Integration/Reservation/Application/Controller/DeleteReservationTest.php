<?php

declare(strict_types=1);

namespace App\Tests\Integration\Reservation\Application\Controller;

use App\Tests\Integration\ApiTestCase;
use DataFixtures\Reservation\ReservationFixture;
use DataFixtures\User\StaffFixture;

final class DeleteReservationTest extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function testStaffCanDeleteReservation(): void
    {
        $response = $this->delete(
            '/reservation/' . ReservationFixture::RESERVERTION_FOR_BOB_TO_REMOVE_UUID,
            auth_token: StaffFixture::BOB_DELETER_TOKEN,
        );

        $get_response = $this->get(
            '/reservation/' . ReservationFixture::RESERVERTION_FOR_BOB_TO_REMOVE_UUID,
            auth_token: StaffFixture::BOB_DELETER_TOKEN,
        );

        static::assertStatusCode(204, $response);
        static::assertStatusCode(404, $get_response);
    }
}
