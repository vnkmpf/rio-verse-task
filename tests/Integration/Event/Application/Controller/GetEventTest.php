<?php

declare(strict_types=1);

namespace App\Tests\Integration\Event\Application\Controller;

use DataFixtures\Event\EventFixture;
use DataFixtures\User\StaffFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class GetEventTest extends WebTestCase
{
    public static function setUpBeforeClass(): void
    {
        shell_exec(sprintf(
            'php "%s/../../../../../bin/console" doctrine:fixtures:load --env test --no-interaction --append --group=EventFixture',
            __DIR__,
        ));
    }

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
}
