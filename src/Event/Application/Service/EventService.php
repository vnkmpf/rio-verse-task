<?php

declare(strict_types=1);

namespace App\Event\Application\Service;

use App\Event\Domain\Entity\Event;
use App\Event\Domain\Repository\EventRepository;
use App\Shared\Infrastructure\Repository\EntityNotFoundException;
use App\Shared\Infrastructure\TimeProvider;
use Symfony\Component\Uid\Uuid;

final class EventService
{
    public function __construct(
        private readonly EventRepository $event_repository,
        private readonly TimeProvider $time_provider,
    ) {
    }

    public function store(Event $event): Event
    {
        if ($event->date < $this->time_provider->now()->format('Y-m-d')) {
            throw new \InvalidArgumentException('Cannot create event in the past.');
        }

        return $this->event_repository->store($event);
    }

    public function deleteById(Uuid $id): void
    {
        $event = $this->getById($id) ?? throw new EntityNotFoundException();
        $this->event_repository->delete($event);
    }

    public function getById(Uuid $id): ?Event
    {
        return $this->event_repository->getById($id);
    }
}
