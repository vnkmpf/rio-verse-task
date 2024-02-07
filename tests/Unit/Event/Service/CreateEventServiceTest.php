<?php

declare(strict_types=1);

namespace App\Tests\Unit\Event\Service;

use App\Event\Application\Service\EventService;
use App\Event\Domain\Entity\Event;
use App\Event\Domain\EventStatus;
use App\Event\Domain\Repository\EventRepository;
use App\Reservation\Domain\Repository\ReservationRepository;
use App\Service\Domain\Repository\ServiceRepository;
use App\Shared\DataType\DateImmutable;
use App\Shared\Infrastructure\SystemTimeProvider;
use App\Shared\Infrastructure\TimeProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV7;

final class CreateEventServiceTest extends TestCase
{
    public function testStoreEvent(): void
    {
        $event = $this->getEvent();
        $repository = $this->getRepository();
        $service = new EventService(
            $repository,
            new SystemTimeProvider(),
            $this->createMock(ServiceRepository::class),
            $this->createMock(ReservationRepository::class),
        );

        $service->store($event);

        static::assertNotEmpty($repository->data);
    }

    public function testCannotStoreInThePast(): void
    {
        $time_provider = $this->createStub(TimeProvider::class);
        $time_provider->method('now')->willReturn(
            new \DateTimeImmutable('2000-01-01'),
        );
        $event = $this->getEvent('1999-12-31');
        $repository = $this->getRepository();
        $service = new EventService(
            $repository,
            $time_provider,
            $this->createMock(ServiceRepository::class),
            $this->createMock(ReservationRepository::class),
        );

        $this->expectException(\InvalidArgumentException::class);
        $service->store($event);
    }

    public function testCanStoreToday(): void
    {
        $time_provider = $this->createStub(TimeProvider::class);
        $time_provider->method('now')->willReturn(
            new \DateTimeImmutable('2000-01-01'),
        );
        $event = $this->getEvent('2000-01-01');
        $repository = $this->getRepository();
        $service = new EventService(
            $repository,
            $time_provider,
            $this->createMock(ServiceRepository::class),
            $this->createMock(ReservationRepository::class),
        );

        $service->store($event);
        static::assertNotEmpty($repository->data);
    }

    private function getRepository(): EventRepository
    {
        return new class() implements EventRepository {
            /** @var Event[] */
            public array $data = [];

            #[\Override] public function getById(Uuid $id): ?Event
            {
                return $this->data[$id->toRfc4122()] ?? null;
            }

            #[\Override] public function store(Event $event): Event
            {
                $this->data[$event->id->toRfc4122()] = $event;
                return $event;
            }

            #[\Override] public function delete(Event $event): void
            {
                throw new \RuntimeException('Method not implemented');
            }
        };
    }

    private function getEvent(string $time = '2100-05-30'): Event
    {
        return new Event(
            new UuidV7(),
            480,
            640,
            new DateImmutable($time),
            new UuidV7(),
            EventStatus::ACTIVE,
            new UuidV7(),
        );
    }
}
