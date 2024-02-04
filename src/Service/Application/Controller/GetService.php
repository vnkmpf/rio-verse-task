<?php

declare(strict_types=1);

namespace App\Service\Application\Controller;

use App\Service\Domain\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Uid\Uuid;

#[Route('/service/{id}', methods: ['GET'], requirements: ['id' => Requirement::UUID_V7])]
final class GetService extends AbstractController
{
    public function __construct(
        private readonly ServiceRepository $service_repository,
    ) {
    }

    public function __invoke(Uuid $id): Response
    {
        return new JsonResponse($this->service_repository->findById($id), 200);
    }
}
