<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service\Application\Controller;

use App\Tests\Integration\ApiTestCase;
use DataFixtures\User\StaffFixture;

final class CreateServiceTest extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
    }

    public function testCanCreateService(): void
    {
        $response = $this->post('/services', auth_token: StaffFixture::ALICE_TOKEN, content: <<< JSON
            {
                "cancellation_limit": 1440,
                "capacity": 10,
                "description": "Test",
                "duration": 60,
                "name": "Scuba diving"
            }
            JSON
        );
        $created_service = $this->getResponseObject($response);

        static::assertStatusCode(201, $response);
        static::assertSame(1440, $created_service->cancellation_limit);
        static::assertSame(10, $created_service->capacity);
        static::assertSame('Test', $created_service->description);
        static::assertSame(60, $created_service->duration);
        static::assertSame('Scuba diving', $created_service->name);
        static::assertUuid($created_service->id);
    }

    public function testNonJsonDataSent(): void
    {
        $response = $this->post('/services', content: 'not-json-data', auth_token: StaffFixture::ALICE_TOKEN);
        $response_data = $this->getResponseObject($response);

        static::assertStatusCode(400, $response);
        static::assertSame('application/problem+json', $response->headers->get('Content-Type'));
        static::assertSame('Request data is not valid JSON.', $response_data->detail);
    }

    public function testMissingRequiredData(): void
    {
        $response = $this->post('/services', auth_token: StaffFixture::ALICE_TOKEN, content: <<< JSON
            {
                "cancellation_limit": 1440,
                "capacity": 10,
                "description": "Test",
                "duration": 60
            }
            JSON
        );
        $response_data = $this->getResponseObject($response);

        static::assertStatusCode(400, $response);
        static::assertSame('application/problem+json', $response->headers->get('Content-Type'));
        static::assertSame('Missing data "name".', $response_data->detail);
    }

    public function testDataConstraintsReturnBadData(): void
    {
        $response = $this->post('/services', auth_token: StaffFixture::ALICE_TOKEN, content: <<< JSON
            {
                "cancellation_limit": 1440,
                "capacity": -10,
                "description": "Test",
                "duration": 60,
                "name": "Scuba diving"
            }
            JSON
        );
        $response_data = $this->getResponseObject($response);

        static::assertStatusCode(400, $response);
        static::assertSame('application/problem+json', $response->headers->get('Content-Type'));
        static::assertSame('Capacity has to be positive', $response_data->detail);
    }

    public function testServiceIsCreatedWithMyUuidAsStaffId(): void
    {
        $response = $this->post('/services', auth_token: StaffFixture::ALICE_TOKEN, content: <<< JSON
            {
                "cancellation_limit": 1440,
                "capacity": 10,
                "description": "Test",
                "duration": 60,
                "name": "Scuba diving"
            }
            JSON
        );
        $response_data = $this->getResponseObject($response);

        static::assertSame(StaffFixture::ALICE_UUID, $response_data->staff);
    }

    public function testUnauthorizedRequestErrs(): void
    {
        $response = $this->post('/services', content: <<< JSON
            {
                "cancellation_limit": 1440,
                "capacity": 10,
                "description": "Test",
                "duration": 60,
                "name": "Scuba diving"
            }
            JSON
        );
        $response_data = $this->getResponseObject($response);

        static::assertStatusCode(401, $response);
        static::assertSame('application/problem+json', $response->headers->get('Content-Type'));
        static::assertSame('Request not authorized.', $response_data->detail);
    }

    public function testUserWithTokenDoesNotExist(): void
    {
        $response = $this->post('/services', auth_token: '0000000000000000', content: <<< JSON
            {
                "cancellation_limit": 1440,
                "capacity": 10,
                "description": "Test",
                "duration": 60,
                "name": "Scuba diving"
            }
            JSON,
        );
        $response_data = $this->getResponseObject($response);

        static::assertStatusCode(401, $response);
        static::assertSame('application/problem+json', $response->headers->get('Content-Type'));
        static::assertSame('Request not authorized.', $response_data->detail);
    }
}
