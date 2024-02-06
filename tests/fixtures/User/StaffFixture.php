<?php

declare(strict_types=1);

namespace DataFixtures\User;

use App\User\Domain\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\UuidV7;

final class StaffFixture extends Fixture implements FixtureGroupInterface
{
    public const string STAFF_UUID = '018d7583-7f63-7cd1-9858-0d50293544ef';

    public const string STAFF_DELETER_UUID = '018d7fee-64c1-7fd7-941e-671bb3d0dda2';

    public const string STAFF_TOKEN = '0123456789abcdef0123456789abcdef';

    public const string STAFF_DELETER_TOKEN = 'deleter-deleter-deleter';

    #[\Override]
    public function load(ObjectManager $manager)
    {
        $manager->persist(new User(
            new UuidV7(static::STAFF_UUID),
            'Alice Doe',
            'alice@example.com',
            static::STAFF_TOKEN,
        ));
        $manager->persist(new User(
            new UuidV7(static::STAFF_DELETER_UUID),
            'Bob The Deleter',
            'bob@example.com',
            static::STAFF_DELETER_TOKEN,
        ));
        $manager->flush();
    }

    /**
     * @return string[]
     */
    #[\Override]
    public static function getGroups(): array
    {
        return ['everytime'];
    }
}
