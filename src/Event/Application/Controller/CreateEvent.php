<?php

declare(strict_types=1);

namespace App\Event\Application\Controller;

use App\Event\Domain\Entity\Event;
use App\Event\Domain\EventStatus;
use App\Shared\DataType\DateImmutable;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\UuidV7;

#[Route('/events', methods: ['POST'])]
final class CreateEvent extends AbstractController
{
    public function __invoke(Request $request): Response
    {
        $post_data = $this->decodeJsonContent($request);
        $event = new Event(
            new UuidV7(),
            $post_data->start,
            $post_data->end,
            new DateImmutable($post_data->date),
            new UuidV7($post_data->service_id),
            EventStatus::ACTIVE,
        );
        return new JsonResponse($event, 201);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws \JsonException
     */
    private function decodeJsonContent(Request $request): object
    {
        return (object) json_decode(
            $request->getContent(),
            false,
            512,
            JSON_THROW_ON_ERROR,
        );
    }
}
