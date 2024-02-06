<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service\Application\Controller;

use App\Tests\Integration\ApiTestCase;
use DataFixtures\Service\ServiceFixture;
use DataFixtures\User\StaffFixture;

final class GetServiceTest extends ApiTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient(server: [
            'HTTP_AUTHORIZATION' => 'token ' . StaffFixture::ALICE_TOKEN,
        ]);
    }

    public function testGetService(): void
    {
        $response = $this->get('/service/' . ServiceFixture::SPANISH_101_UUID);

        static::assertJsonStringEqualsJsonString(
            json_encode(ServiceFixture::getHardCodedService(), JSON_THROW_ON_ERROR),
            $response->getContent(),
        );
    }

    public function testTryingToGetNonExistingService(): void
    {
        $response = $this->get('/service/018d7500-d784-7956-bec7-b09dbf2ed679');

        static::assertSame(404, $response->getStatusCode());
    }
}
