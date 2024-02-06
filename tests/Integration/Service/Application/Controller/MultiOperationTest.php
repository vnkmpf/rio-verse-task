<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service\Application\Controller;

use DataFixtures\User\StaffFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class MultiOperationTest extends WebTestCase
{
    public function testCreatedServiceCanBeRetrieved(): void
    {
        $client = static::createClient();
        $client->request('POST', '/services', content: <<< JSON
            {
                "cancellation_limit": 1440,
                "capacity": 10,
                "description": "Test",
                "duration": 60,
                "name": "Scuba diving"
            }
            JSON,
            server: [
                'HTTP_AUTHORIZATION' => 'token ' . StaffFixture::ALICE_TOKEN,
            ],
        );

        $post_response = $client->getResponse();
        $created_uuid = json_decode(
            $post_response->getContent(),
            false,
            512,
            JSON_THROW_ON_ERROR,
        )->id;

        $client->request('GET', '/service/' . $created_uuid, server: [
            'HTTP_AUTHORIZATION' => 'token ' . StaffFixture::ALICE_TOKEN,
        ]);
        $response = $client->getResponse();

        static::assertSame(200, $response->getStatusCode());
    }
}
