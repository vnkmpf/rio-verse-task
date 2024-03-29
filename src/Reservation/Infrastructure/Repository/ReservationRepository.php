<?php

declare(strict_types=1);

namespace App\Reservation\Infrastructure\Repository;

use App\Reservation\Domain\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Reservation>
 */
final class ReservationRepository extends ServiceEntityRepository implements \App\Reservation\Domain\Repository\ReservationRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    #[\Override]
    public function store(Reservation $reservation): void
    {
        $this->getEntityManager()->persist($reservation);
        $this->getEntityManager()->flush();
    }

    /**
     * @return Reservation[]
     */
    #[\Override]
    public function getReservationsForEvent(\App\Event\Domain\Entity\Event $event): array
    {
        return $this->findBy(['event_id' => $event->id]);
    }

    #[\Override]
    public function getById(Uuid $id): ?Reservation
    {
        return $this->find($id);
    }

    #[\Override]
    public function delete(Reservation $reservation): void
    {
        $this->getEntityManager()->remove($reservation);
        $this->getEntityManager()->flush();
    }
}
