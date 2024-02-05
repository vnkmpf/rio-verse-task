<?php

declare(strict_types=1);

namespace App\Event\Application\Controller;

use App\Event\Domain\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Uid\Uuid;

#[Route('/event/{id}', methods: ['GET'], requirements: ['id' => Requirement::UUID_V7])]
final class GetEvent extends AbstractController
{
    public function __construct(
        private readonly EventRepository $event_repository,
    ) {
    }

    public function __invoke(Uuid $id): Response
    {
        $event = $this->event_repository->getById($id);

        if ($event === null) {
            return new JsonResponse(sprintf('Event "%s" not found.', $id), 404);
        }

        return new JsonResponse($event, 200);
    }
}
