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
    public const string ALICE_UUID = '018d7583-0000-7000-b000-f001f0000001';

    public const string BOB_DELETER_UUID = '018d7fee-0001-7000-9000-f001f0000002';

    public const string ALICE_TOKEN = '0123456789abcdef0123456789abcdef';

    public const string BOB_DELETER_TOKEN = 'deleter-deleter-deleter';

    #[\Override]
    public function load(ObjectManager $manager)
    {
        $manager->persist(new User(
            new UuidV7(static::ALICE_UUID),
            'Alice Doe',
            'alice@example.com',
            static::ALICE_TOKEN,
        ));
        $manager->persist(new User(
            new UuidV7(static::BOB_DELETER_UUID),
            'Bob The Deleter',
            'bob@example.com',
            static::BOB_DELETER_TOKEN,
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
