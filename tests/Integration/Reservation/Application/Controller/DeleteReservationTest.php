<?php

declare(strict_types=1);

namespace App\Tests\Integration\Reservation\Application\Controller;

use App\Tests\Integration\ApiTestCase;
use DataFixtures\Reservation\ReservationFixture;
use DataFixtures\User\StaffFixture;
use DataFixtures\User\UserFixture;

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

    public function testReserveeCanDeleteReservation(): void
    {
        $response = $this->delete(
            '/reservation/' . ReservationFixture::RESERVERTION_FOR_CAROL_TO_REMOVE_UUID,
            auth_token: UserFixture::CAROL_TOKEN,
        );

        $get_response = $this->get(
            '/reservation/' . ReservationFixture::RESERVERTION_FOR_CAROL_TO_REMOVE_UUID,
            auth_token: UserFixture::CAROL_TOKEN,
        );

        static::assertStatusCode(204, $response);
        static::assertStatusCode(404, $get_response);
    }

    public function testCannotDeleteSomeoneElsesReservation(): void
    {
        $response = $this->delete(
            '/reservation/' . ReservationFixture::BOBS_RESERVERTION_ALICE_WANTS_TO_DELETE_UUID,
            auth_token: StaffFixture::ALICE_TOKEN,
        );

        $get_response = $this->get(
            '/reservation/' . ReservationFixture::BOBS_RESERVERTION_ALICE_WANTS_TO_DELETE_UUID,
            auth_token: StaffFixture::BOB_DELETER_TOKEN,
        );

        static::assertStatusCode(404, $response);
        static::assertStatusCode(200, $get_response);
    }
}
