<?php

declare(strict_types=1);

namespace App\Tests\Integration\Event\Application\Controller;

use DataFixtures\Service\ServiceFixture;
use DataFixtures\User\StaffFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class MultiOperationTest extends WebTestCase
{
    public function testCreatedEventCanBeRetrieved(): void
    {
        $service_uuid = ServiceFixture::SERVICE_UUID;
        $client = static::createClient();
        $client->request('POST', '/events', content: <<< JSON
            {
                "start": 480,
                "end": 540,
                "date": "2000-12-31",
                "service_id": "{$service_uuid}"
            }
            JSON,
            server: [
                'HTTP_AUTHORIZATION' => 'token ' . StaffFixture::STAFF_TOKEN,
            ],
        );

        $post_response = $client->getResponse();
        $created_uuid = json_decode(
            $post_response->getContent(),
            false,
            512,
            JSON_THROW_ON_ERROR,
        )->id;

        $client->request('GET', '/event/' . $created_uuid, server: [
            'HTTP_AUTHORIZATION' => 'token ' . StaffFixture::STAFF_TOKEN,
        ]);
        $response = $client->getResponse();
        $created_service = json_decode($response->getContent(), false, 512, JSON_THROW_ON_ERROR);

        static::assertSame(200, $response->getStatusCode());
        static::assertSame(480, $created_service->start);
        static::assertSame(540, $created_service->end);
        static::assertSame('2000-12-31', $created_service->date);
        static::assertSame($service_uuid, $created_service->service_id);
    }
}
