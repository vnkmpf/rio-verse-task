<?php

declare(strict_types=1);

namespace App\Shared\DataType;

final class DateImmutable implements \JsonSerializable, \Stringable
{
    private \DateTimeImmutable $value;

    public function __construct(string $value)
    {
        try {
            $this->value = (new \DateTimeImmutable($value));
        } catch (\DateMalformedStringException) {
            throw new \InvalidArgumentException(sprintf('Invalid date "%s"', $value));
        }
    }

    public function __toString(): string
    {
        return $this->value->format('Y-m-d');
    }

    #[\Override]
    public function jsonSerialize(): string
    {
        return $this->__toString();
    }
}
