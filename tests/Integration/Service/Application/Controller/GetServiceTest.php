<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service\Application\Controller;

use DataFixtures\Service\ServiceFixture;
use DataFixtures\User\StaffFixture;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class GetServiceTest extends WebTestCase
{
    public static function setUpBeforeClass(): void
    {
        shell_exec(sprintf(
            'php "%s/../../../../../bin/console" doctrine:fixtures:load --env test --no-interaction --append --group=ServiceFixture',
            __DIR__,
        ));
    }

    public function testGetService(): void
    {
        $client = static::createClient(server: [
            'HTTP_AUTHORIZATION' => 'token ' . StaffFixture::STAFF_TOKEN,
        ]);
        $client->request('GET', '/service/' . ServiceFixture::SERVICE_UUID);

        $response = $client->getResponse();

        static::assertJsonStringEqualsJsonString(
            json_encode(ServiceFixture::getHardCodedService(), JSON_THROW_ON_ERROR),
            $response->getContent(),
        );
    }

    public function testTryingToGetNonExistingService(): void
    {
        $client = static::createClient(server: [
            'HTTP_AUTHORIZATION' => 'token ' . StaffFixture::STAFF_TOKEN,
        ]);
        $client->request('GET', '/service/018d7500-d784-7956-bec7-b09dbf2ed679');

        $response = $client->getResponse();

        static::assertSame(404, $response->getStatusCode());
    }
}
