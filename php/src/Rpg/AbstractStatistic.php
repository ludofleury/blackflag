<?php

namespace Rpg;

use Rpg\Exception\InvalidStatisticRequirementsException;
use Rpg\Exception\OutOfRangeStatisticValueException;
use Rpg\Exception\UnsupportedStatisticException;

abstract class AbstractStatistic
{
    protected string $name;
    protected int $value;
    protected ?int $minimum;
    protected ?int $maximum;

    public function __construct(string $name, int $value, ?int $minimum = null, ?int $maximum = null)
    {
        if ($maximum !== null && $minimum !== null && $minimum > $maximum) {
            throw new InvalidStatisticRequirementsException(static::class, $name, $minimum, $maximum);
        }

        if ($minimum !== null && $value < $minimum) {
            throw new OutOfRangeStatisticValueException(static::class, $name, $minimum, $value);
        }

        if ($maximum !== null && $value > $maximum) {
            throw new OutOfRangeStatisticValueException(static::class, $name, $maximum, $value);
        }

        $this->name = $name;
        $this->value = $value;
        $this->minimum = $minimum;
        $this->maximum = $maximum;
    }

    public function __toString(): string
    {
        return sprintf('%s: %d', $this->name, $this->value);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}