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
            'HTTP_AUTHORIZATION' => 'token ' . StaffFixture::STAFF_TOKEN,
        ]);
        $client->request('DELETE', '/event/' . EventFixture::EVENT_TO_DELETE_UUID);
        $response = $client->getResponse();

        static::assertSame(204, $response->getStatusCode());
    }
}
