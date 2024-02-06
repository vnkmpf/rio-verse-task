<?php

declare(strict_types=1);

namespace App\Tests\Integration\Event\Application\Controller;

use DataFixtures\Event\EventFixture;
use DataFixtures\User\StaffFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class GetEventTest extends WebTestCase
{
    public function testCanGetEvent(): void
    {
        $client = static::createClient(server: [
            'HTTP_AUTHORIZATION' => 'token ' . StaffFixture::STAFF_TOKEN,
        ]);
        $client->request('GET', '/event/' . EventFixture::EVENT_UUID);
        $response = $client->getResponse();

        static::assertJsonStringEqualsJsonString(
            json_encode(EventFixture::getHardCodedService(), JSON_THROW_ON_ERROR),
            $response->getContent(),
        );
    }

    public function testTryingToGetNonExistingEvent(): void
    {
        $client = static::createClient(server: [
            'HTTP_AUTHORIZATION' => 'token ' . StaffFixture::STAFF_TOKEN,
        ]);
        $client->request('GET', '/event/018d7b37-8c6c-7d36-93e4-0326b84016e8');
        $response = $client->getResponse();

        static::assertSame(404, $response->getStatusCode());
    }
}
