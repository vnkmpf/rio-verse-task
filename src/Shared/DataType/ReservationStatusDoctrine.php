<?php

declare(strict_types=1);

namespace App\Shared\DataType;

use App\Reservation\Domain\ReservationStatus;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Platforms\AbstractPlatform;

final class ReservationStatusDoctrine extends Type
{
    #[\Override]
    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'text';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): mixed
    {
        if (is_string($value)) {
            return ReservationStatus::from($value);
        }

        return $value;
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if ($value instanceof ReservationStatus) {
            return $value->value;
        }

        return $value;
    }
}
