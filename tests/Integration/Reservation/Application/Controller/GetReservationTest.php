<?php

declare(strict_types=1);

namespace App\Tests\Integration\Reservation\Application\Controller;

use App\Tests\Integration\ApiTestCase;
use DataFixtures\Reservation\ReservationFixture;
use DataFixtures\User\StaffFixture;

final class GetReservationTest extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public function testStaffCanRetrieve(): void
    {
        $response = $this->get(
            '/reservation/' . ReservationFixture::RESERVERTION_TO_RETRIEVE_UUID,
            auth_token: StaffFixture::ALICE_TOKEN,
        );
        $data = $this->getResponseObject($response);

        static::assertStatusCode(200, $response);
        static::assertJsonStringEqualsJsonString(
            json_encode(ReservationFixture::getReservationToRetrieve(), JSON_THROW_ON_ERROR),
            json_encode($data, JSON_THROW_ON_ERROR),
        );
    }
}
