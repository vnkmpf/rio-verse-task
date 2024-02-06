<?php

declare(strict_types=1);

namespace App\Tests\Integration\Event\Application\Controller;

use App\Tests\Integration\ApiTestCase;
use DataFixtures\Event\EventFixture;
use DataFixtures\User\StaffFixture;

final class GetEventTest extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient(server: [
            'HTTP_AUTHORIZATION' => 'token ' . StaffFixture::ALICE_TOKEN,
        ]);
    }

    public function testCanGetEvent(): void
    {
        $response = $this->get('/event/' . EventFixture::EVENT_UUID);

        static::assertJsonStringEqualsJsonString(
            json_encode(EventFixture::getHardCodedService(), JSON_THROW_ON_ERROR),
            $response->getContent(),
        );
    }

    public function testTryingToGetNonExistingEvent(): void
    {
        $response = $this->get('/event/018d7b37-8c6c-7d36-93e4-0326b84016e8');

        static::assertStatusCode(404, $response);
    }
}
