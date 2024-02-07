<?php

declare(strict_types=1);

namespace DataFixtures\User;

use App\User\Domain\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\UuidV7;

final class UserFixture extends Fixture implements FixtureGroupInterface
{
    public const string CAROL_UUID = '018d7583-0000-7000-b000-f001f0000003';

    public const string CAROL_TOKEN = 'carol-token';

    #[\Override]
    public function load(ObjectManager $manager)
    {
        $manager->persist(new User(
            new UuidV7(static::CAROL_UUID),
            'Carol Christmas',
            'carol@example.com',
            static::CAROL_TOKEN,
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
