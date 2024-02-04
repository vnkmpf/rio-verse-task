<?php

declare(strict_types=1);

namespace App\Service\Application\Controller;

use App\Service\Domain\Entity\Service;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\UuidV7;

#[Route('/services', methods: ['POST'])]
final class CreateService extends AbstractController
{
    public function __construct()
    {
    }

    public function __invoke(): Response
    {
        $post_data = $this->decodeJsonContent();
        $service = new Service(
            new UuidV7(),
            $post_data->name ?? throw new BadRequestHttpException('name'),
            $post_data->duration ?? throw new BadRequestHttpException('duration'),
        $post_data->capacity ?? throw new BadRequestHttpException('capacity'),
            $post_data->description ?? throw new BadRequestHttpException('description'),
            $post_data->cancellation_limit ?? throw new BadRequestHttpException('cancellation_limit'),
            new UuidV7(),
        );

        return new JsonResponse($service, 201);
    }

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     * @throws \JsonException
     */
    private function decodeJsonContent(): object
    {
        return json_decode(
            $this->container->get('request_stack')->getCurrentRequest()?->getContent(),
            false,
            512,
            JSON_THROW_ON_ERROR,
        );
    }
}
