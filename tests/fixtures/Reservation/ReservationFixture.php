<?php

declare(strict_types=1);

namespace DataFixtures\Reservation;

use App\Reservation\Domain\Entity\Reservation;
use App\Reservation\Domain\ReservationStatus;
use DataFixtures\Event\EventFixture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\UuidV7;

final class ReservationFixture extends Fixture implements FixtureGroupInterface
{
    #[\Override]
    public static function getGroups(): array
    {
        return ['everytime'];
    }

    #[\Override]
    public function load(ObjectManager $manager)
    {
        $manager->persist(new Reservation(
            new UuidV7(),
            'John Doe',
            'john.doe@example.com',
            null,
            new UuidV7(EventFixture::ALICE_FULL_CAPACITY_EVENT_UUID),
            '',
            ReservationStatus::CONFIRMED,
        ));
        $manager->persist(new Reservation(
            new UuidV7(),
            'Jane Doe',
            'jane.doe@example.com',
            null,
            new UuidV7(EventFixture::ALICE_FULL_CAPACITY_EVENT_UUID),
            '',
            ReservationStatus::CONFIRMED,
        ));

        $manager->flush();
    }
}
