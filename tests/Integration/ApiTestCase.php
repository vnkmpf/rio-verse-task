<?php

declare(strict_types=1);

namespace App\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

abstract class ApiTestCase extends WebTestCase
{
    protected \Symfony\Bundle\FrameworkBundle\KernelBrowser $client;

    /**
     * @param array<string, string> $headers
     */
    protected function post(
        string $url,
        ?string $content = null,
        string $auth_token = '',
        array $headers = [],
    ): Response
    {
        if ($auth_token !== '') {
            $headers['HTTP_AUTHORIZATION'] = 'token ' . $auth_token;
        }

        $this->client->request('POST', $url, content: $content, server: $headers);
        return $this->client->getResponse();
    }

    /**
     * @param array<string, string> $headers
     */
    protected function delete(
        string $url,
        ?string $content = null,
        string $auth_token = '',
        array $headers = [],
    ): Response
    {
        if ($auth_token !== '') {
            $headers['HTTP_AUTHORIZATION'] = 'token ' . $auth_token;
        }

        $this->client->request('DELETE', $url, content: $content, server: $headers);
        return $this->client->getResponse();
    }

    protected static function assertUuid(string $var): void
    {
        static::assertMatchesRegularExpression(
            '#^[\\da-f]{8}-[\\da-f]{4}-[\\da-f]{4}-[\\da-f]{4}-[\\da-f]{12}$#i',
            $var,
            "Failed asserting that {$var} is a UUID",
        );
    }

    protected static function assertStatusCode(int $status, Response $response): void
    {
        static::assertSame(
            $status,
            $response->getStatusCode(),
            "Failed asserting that status code is {$status}",
        );
    }

    /**
     * @throws \JsonException
     */
    protected function getResponseObject(Response $response): object
    {
        return (object) json_decode($response->getContent(), false, 512, JSON_THROW_ON_ERROR);
    }
}
