<?php

declare(strict_types=1);

namespace DataFixtures\Event;

use App\Event\Domain\Entity\Event;
use App\Event\Domain\EventStatus;
use App\Shared\DataType\DateImmutable;
use DataFixtures\Service\ServiceFixture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\UuidV7;

final class EventFixture extends Fixture implements FixtureGroupInterface
{
    public const string BOBS_EVENT_TO_DELETE_UUID = '018d7aa8-0000-7a77-908e-000000000001';

    public const string ALICES_EVENT_TO_DELETE_UUID = '018d7f76-0000-7dca-87be-000000000002';

    public const string EVENT_UUID = '018d7aa8-0000-7a77-908e-000000000003';

    public function load(ObjectManager $manager): void
    {
        $manager->persist(new Event(
            new UuidV7(static::BOBS_EVENT_TO_DELETE_UUID),
            480,
            540,
            new DateImmutable('2000-01-01'),
            new UuidV7(ServiceFixture::GEOLOGY_BASISC_UUID),
            EventStatus::ACTIVE,
        ));
        $manager->persist(static::getHardCodedService(static::ALICES_EVENT_TO_DELETE_UUID));
        $manager->persist(static::getHardCodedService(static::EVENT_UUID));
        $manager->flush();
    }

    public static function getHardCodedService(string $uuid = self::EVENT_UUID): Event
    {
        return new Event(
            new UuidV7($uuid),
            480,
            540,
            new DateImmutable('2000-01-01'),
            new UuidV7(ServiceFixture::SPANISH_101_UUID),
            EventStatus::ACTIVE,
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
