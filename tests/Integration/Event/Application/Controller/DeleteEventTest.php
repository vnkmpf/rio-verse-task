<?php

declare(strict_types=1);

namespace App\Tests\Integration\Event\Application\Controller;

use DataFixtures\Event\EventFixture;
use DataFixtures\User\StaffFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class DeleteEventTest extends WebTestCase
{
    public function testCanDeleteEvent(): void
    {
        $client = static::createClient(server: [
            'HTTP_AUTHORIZATION' => 'token ' . StaffFixture::BOB_DELETER_TOKEN,
        ]);
        $client->request('DELETE', '/event/' . EventFixture::BOBS_EVENT_TO_DELETE_UUID);
        $response = $client->getResponse();

        static::assertSame(204, $response->getStatusCode());
    }

    public function testCannotDeleteNonExistingEvent(): void
    {
        $client = static::createClient(server: [
            'HTTP_AUTHORIZATION' => 'token ' . StaffFixture::ALICE_TOKEN,
        ]);
        $client->request('DELETE', '/event/018d7f65-35ae-75bf-882f-d005d29fc02c');
        $response = $client->getResponse();

        static::assertSame(404, $response->getStatusCode());
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
        $client = static::createClient(server: [
            'HTTP_AUTHORIZATION' => 'token ' . StaffFixture::BOB_DELETER_TOKEN,
        ]);
        $client->request('DELETE', '/event/' . EventFixture::ALICES_EVENT_TO_DELETE_UUID);
        $response = $client->getResponse();

        static::assertSame(404, $response->getStatusCode());
    }
}
