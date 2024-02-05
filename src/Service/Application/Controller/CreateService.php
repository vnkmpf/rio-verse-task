<?php

declare(strict_types=1);

namespace App\Service\Application\Controller;

use App\Service\Domain\Entity\Service;
use App\Service\Domain\Repository\ServiceRepository;
use App\Shared\Application\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\UuidV7;

#[Route('/services', methods: ['POST'])]
final class CreateService extends AbstractController
{
    public function __construct(
        private readonly ServiceRepository $service_repository,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        try {
            $post_data = $this->decodeJsonContent($request);
            $service = new Service(
                new UuidV7(),
                $post_data->name ?? throw new BadRequestHttpException('name'),
                $post_data->duration ?? throw new BadRequestHttpException('duration'),
                $post_data->capacity ?? throw new BadRequestHttpException('capacity'),
                $post_data->description ?? throw new BadRequestHttpException('description'),
                $post_data->cancellation_limit ?? throw new BadRequestHttpException('cancellation_limit'),
                new UuidV7($request->headers->get('X-user-id')),
            );
            $this->service_repository->save($service);
        } catch (\Exception $e) {
            return $this->sendJsonProblem($e);
        }

        return new JsonResponse($service, 201);
    }
}
