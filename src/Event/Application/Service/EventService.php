<?php

declare(strict_types=1);

namespace App\Event\Application\Service;

use App\Event\Domain\Entity\Event;
use App\Event\Domain\Repository\EventRepository;
use App\Reservation\Domain\Repository\ReservationRepository;
use App\Service\Domain\Repository\ServiceRepository;
use App\Shared\Infrastructure\Repository\EntityNotFoundException;
use App\Shared\Infrastructure\TimeProvider;
use Symfony\Component\Uid\Uuid;

final class EventService
{
    public function __construct(
        private readonly EventRepository $event_repository,
        private readonly TimeProvider $time_provider,
        private readonly ServiceRepository $service_repository,
        private readonly ReservationRepository $reservation_repository,
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

    public function canBook(Event $event): bool
    {
        $service = $this->service_repository->findById($event->service_id);
        $reservation_count = count($this->reservation_repository->getReservationsForEvent($event));

        if ($service === null) {
            return false;
        }

        if ($service->capacity > $reservation_count) {
            return true;
        }

        return false;
    }
}
