<?php

declare(strict_types=1);

namespace App\Tests\Integration\Event\Application\Controller;

use App\Tests\Integration\ApiTestCase;
use DataFixtures\Service\ServiceFixture;
use DataFixtures\User\StaffFixture;

final class MultiOperationTest extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
    }

    public function testCreatedEventCanBeRetrieved(): void
    {
        $service_uuid = ServiceFixture::SPANISH_101_UUID;
        $post_response = $this->post('/events', auth_token: StaffFixture::ALICE_TOKEN, content: <<< JSON
            {
                "start": 480,
                "end": 540,
                "date": "2100-12-31",
                "service_id": "{$service_uuid}"
            }
            JSON,
        );
        $created_uuid = $this->getResponseObject($post_response)->id;

        $response = $this->get('/event/' . $created_uuid, auth_token: StaffFixture::ALICE_TOKEN);
        $created_service = $this->getResponseObject($response);

        static::assertStatusCode(200, $response);
        static::assertSame(480, $created_service->start);
        static::assertSame(540, $created_service->end);
        static::assertSame('2100-12-31', $created_service->date);
        static::assertSame($service_uuid, $created_service->service_id);
    }

    public function testEventIsDeleted(): void
    {
        $service_uuid = ServiceFixture::GEOLOGY_BASISC_UUID;

        $post_response = $this->post('/events', auth_token: StaffFixture::BOB_DELETER_TOKEN, content: <<< JSON
            {
                "start": 960,
                "end": 1000,
                "date": "2100-12-31",
                "service_id": "{$service_uuid}"
            }
            JSON,
        );
        $uuid = $this->getResponseObject($post_response)->id;

        $this->delete('/event/' . $uuid, auth_token: StaffFixture::BOB_DELETER_TOKEN);
        $response = $this->get('/event/' . $uuid, auth_token: StaffFixture::BOB_DELETER_TOKEN);

        static::assertStatusCode(404, $response);
    }
}
