<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service\Application\Controller;

use App\Tests\Integration\ApiTestCase;
use DataFixtures\User\StaffFixture;

final class MultiOperationTest extends ApiTestCase
{
    public function testCreatedServiceCanBeRetrieved(): void
    {
        $this->client = static::createClient();
        $post_response = $this->post('/services', auth_token: StaffFixture::ALICE_TOKEN, content: <<< JSON
            {
                "cancellation_limit": 1440,
                "capacity": 10,
                "description": "Test",
                "duration": 60,
                "name": "Scuba diving"
            }
            JSON,
        );
        $created_uuid = $this->getResponseObject($post_response)->id;
        $response = $this->get('/service/' . $created_uuid, auth_token: StaffFixture::ALICE_TOKEN);

        static::assertStatusCode(200, $response);
    }
}
