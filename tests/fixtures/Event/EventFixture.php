<?php

declare(strict_types=1);

namespace DataFixtures\Event;

use App\Event\Domain\Entity\Event;
use App\Event\Domain\EventStatus;
use App\Shared\DataType\DateImmutable;
use DataFixtures\Service\ServiceFixture;
use DataFixtures\User\StaffFixture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\UuidV7;

final class EventFixture extends Fixture implements FixtureGroupInterface
{
    public const string BOBS_EVENT_TO_DELETE_UUID = '018d7aa8-0000-7a77-908e-000000000001';

    public const string ALICES_EVENT_TO_DELETE_UUID = '018d7f76-0000-7dca-87be-000000000002';

    public const string ALICES_EVENT_UUID = '018d7aa8-0000-7a77-908e-000000000003';

    public const string BOBS_EVENT_UUID = '018d7aa8-0000-7a77-908e-000000000004';

    public const string ALICE_FULL_CAPACITY_EVENT_UUID = '018d7aa8-0000-7a77-908e-000000000005';

    public const string ALICE_CANCELED_EVENT_UUID = '018d7aa8-0000-7a77-908e-000000000006';

    public function load(ObjectManager $manager): void
    {
        $manager->persist(new Event(
            new UuidV7(static::BOBS_EVENT_TO_DELETE_UUID),
            480,
            540,
            new DateImmutable('2000-01-01'),
            new UuidV7(ServiceFixture::GEOLOGY_BASISC_UUID),
            EventStatus::ACTIVE,
            new UuidV7(StaffFixture::BOB_DELETER_UUID),
        ));
        $manager->persist(static::getHardCodedService(static::ALICES_EVENT_TO_DELETE_UUID));
        $manager->persist(static::getHardCodedService(static::ALICES_EVENT_UUID));
        $manager->persist(new Event(
            new UuidV7(static::BOBS_EVENT_UUID),
            660,
            720,
            new DateImmutable('2000-01-01'),
            new UuidV7(ServiceFixture::GEOLOGY_BASISC_UUID),
            EventStatus::ACTIVE,
            new UuidV7(StaffFixture::BOB_DELETER_UUID),
        ));
        $manager->persist(new Event(
            new UuidV7(static::ALICE_FULL_CAPACITY_EVENT_UUID),
            660,
            720,
            new DateImmutable('2000-01-01'),
            new UuidV7(ServiceFixture::SPANISH_FULL_CAPACITY_UUID),
            EventStatus::ACTIVE,
            new UuidV7(StaffFixture::ALICE_UUID),
        ));
        $manager->persist(new Event(
            new UuidV7(static::ALICE_CANCELED_EVENT_UUID),
            660,
            720,
            new DateImmutable('2000-01-01'),
            new UuidV7(ServiceFixture::SPANISH_101_UUID),
            EventStatus::CANCELED,
            new UuidV7(StaffFixture::ALICE_UUID),
        ));
        $manager->flush();
    }

    public static function getHardCodedService(string $uuid = self::ALICES_EVENT_UUID): Event
    {
        return new Event(
            new UuidV7($uuid),
            480,
            540,
            new DateImmutable('2000-01-01'),
            new UuidV7(ServiceFixture::SPANISH_101_UUID),
            EventStatus::ACTIVE,
            new UuidV7(StaffFixture::ALICE_UUID),
        );
    }

    /**
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['everytime'];
    }
}
