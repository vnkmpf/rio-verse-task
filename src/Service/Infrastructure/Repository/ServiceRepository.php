<?php

declare(strict_types=1);

namespace App\Service\Infrastructure\Repository;

use App\Service\Domain\Entity\Service;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

final class ServiceRepository extends ServiceEntityRepository implements \App\Service\Domain\Repository\ServiceRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Service::class);
    }

    #[\Override]
    public function findById(Uuid $id): ?Service
    {
        return $this->find($id);
    }
}
