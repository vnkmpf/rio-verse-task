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
    public const string SPANISH_101_UUID = '018d7495-0000-7000-a000-f002f0000001';

    public const string GEOLOGY_BASISC_UUID = '018d7495-0000-7000-b000-f002f0000002';

    public const string SPANISH_FULL_CAPACITY_UUID = '018d7495-0000-7000-b000-f002f0000003';

    public function load(ObjectManager $manager): void
    {
        $manager->persist(static::getHardCodedService());
        $manager->persist(new Service(
            new UuidV7(static::GEOLOGY_BASISC_UUID),
            'Geology Basics',
            120,
            5,
            'Geology terminology',
            1440,
            new UuidV7(StaffFixture::BOB_DELETER_UUID),
        ));
        $manager->persist(new Service(
            new UuidV7(static::SPANISH_FULL_CAPACITY_UUID),
            'Spanish - full',
            120,
            2,
            'This Spanish is full',
            1440,
            new UuidV7(StaffFixture::ALICE_UUID),
        ));
        $manager->flush();
    }

    public static function getHardCodedService(): Service
    {
        return new Service(
            new UuidV7(static::SPANISH_101_UUID),
            'Spanish 101',
            60,
            10,
            'Spanish language introduction',
            1440,
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
