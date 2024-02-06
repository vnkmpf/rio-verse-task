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
    public const string EVENT_TO_DELETE_UUID = '018d7aa8-f39e-7a77-908e-c7be3cab6410';

    public const string SOMEONE_ELSES_EVENT_TO_DELETE_UUID = '018d7f76-16ff-7dca-87be-1c96c1d15b33';

    public const string EVENT_UUID = '018d7aa8-f39e-7a77-908e-c7be3cab6409';

    public function load(ObjectManager $manager): void
    {
        $manager->persist(new Event(
            new UuidV7(static::EVENT_TO_DELETE_UUID),
            480,
            540,
            new DateImmutable('2000-01-01'),
            new UuidV7(ServiceFixture::SERVICE_UUID),
            EventStatus::ACTIVE,
        ));
        $manager->persist(static::getHardCodedService(static::SOMEONE_ELSES_EVENT_TO_DELETE_UUID));
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
            new UuidV7(ServiceFixture::SERVICE_UUID),
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
