<?php

declare(strict_types=1);

namespace DataFixtures\Service;

use App\Service\Domain\Entity\Service;
use DataFixtures\User\StaffFixture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\UuidV7;

class ServiceFixture extends Fixture implements FixtureGroupInterface
{
    public const string SERVICE_UUID = '018d7495-e0b0-7874-af29-6d869936f6c8';

    public const string USER_UUID = StaffFixture::STAFF_DELETER_UUID;

    public function load(ObjectManager $manager): void
    {
        $manager->persist(static::getHardCodedService());
        $manager->flush();
    }

    public static function getHardCodedService(): Service
    {
        return new Service(
            new UuidV7(static::SERVICE_UUID),
            'Spanish 101',
            60,
            10,
            'Spanish language introduction',
            1440,
            new UuidV7(static::USER_UUID),
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
