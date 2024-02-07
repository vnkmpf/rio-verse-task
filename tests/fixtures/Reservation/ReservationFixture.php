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
    public const string RESERVERTION_FOR_BOB_TO_REMOVE_UUID = '00000001-0000-7000-a000-00000000dd01';

    public const string RESERVERTION_TO_RETRIEVE_UUID = '00000001-0000-7000-a000-000000000002';

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
        $manager->persist(new Reservation(
            new UuidV7(static::RESERVERTION_FOR_BOB_TO_REMOVE_UUID),
            'Jane Doe',
            'jane.doe@example.com',
            null,
            new UuidV7(EventFixture::BOBS_EVENT_UUID),
            '',
            ReservationStatus::CONFIRMED,
        ));
        $manager->persist(static::getReservationToRetrieve());

        $manager->flush();
    }

    public static function getReservationToRetrieve(): Reservation
    {
        return new Reservation(
            new UuidV7(static::RESERVERTION_TO_RETRIEVE_UUID),
            'Jane Doe',
            'jane.doe@example.com',
            null,
            new UuidV7(EventFixture::ALICES_EVENT_UUID),
            '',
            ReservationStatus::CONFIRMED,
        );
    }
}
