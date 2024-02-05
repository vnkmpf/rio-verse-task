<?php

declare(strict_types=1);

namespace App\Shared\DataType;

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

final class DateImmutableDoctrine extends Type
{
    #[\Override]
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'DATE';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        if (is_string($value)) {
            return new DateImmutable($value);
        }

        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if ($value instanceof DateImmutable) {
            return (string) $value;
        }

        return $value;
    }
}
