<?php

declare(strict_types=1);

namespace App\Tests\Integration\Event\Application\Controller;

use App\Tests\Integration\ApiTestCase;
use DataFixtures\Event\EventFixture;
use DataFixtures\User\StaffFixture;

final class DeleteEventTest extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
    }

    public function testCanDeleteEvent(): void
    {
        $response = $this->delete(
            '/event/' . EventFixture::BOBS_EVENT_TO_DELETE_UUID,
            auth_token: StaffFixture::BOB_DELETER_TOKEN,
        );

        static::assertStatusCode(204, $response);
    }

    public function testCannotDeleteNonExistingEvent(): void
    {
        $response = $this->delete(
            '/event/018d7f65-35ae-75bf-882f-d005d29fc02c',
            auth_token: StaffFixture::ALICE_TOKEN,
        );

        static::assertStatusCode(404, $response);
    }

    /**
     * When deleting someone else's event, I get 404,
     * to avoid disclosing there is such event in DB.
     *
     * If several staff roles where created, and they didn't
     * have permission, 403 would be returned.
     */
    public function testCannotDeleteEventThatIsNotMine(): void
    {
        $response = $this->delete(
            '/event/' . EventFixture::ALICES_EVENT_TO_DELETE_UUID,
            auth_token: StaffFixture::BOB_DELETER_TOKEN,
        );

        static::assertStatusCode(404, $response);
    }
}
