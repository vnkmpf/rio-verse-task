<?php

declare(strict_types=1);

namespace DataFixtures\Event;

use App\Event\Domain\Entity\Event;
use App\Event\Domain\EventStatus;
use App\Shared\DataType\DateImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\UuidV7;

final class EventFixture extends Fixture implements FixtureGroupInterface
{
    public const string EVENT_UUID = '018d7aa8-f39e-7a77-908e-c7be3cab6409';

    public const string SERVICE_UUID = '018d7495-e0b0-7874-af29-6d869936f6c8';

    public function load(ObjectManager $manager): void
    {
        $manager->persist(static::getHardCodedService());
        $manager->flush();
    }

    public static function getHardCodedService(): Event
    {
        return new Event(
            new UuidV7(static::EVENT_UUID),
            480,
            540,
            new DateImmutable('2000-01-01'),
            new UuidV7(static::SERVICE_UUID),
            EventStatus::ACTIVE,
        );
    }

    /**
     * @return string[]
     */
    public static function getGroups(): array
    {
        return ['EventFixture'];
    }
}
