<?php
declare(strict_types=1);

namespace Rpg;

use Rpg\Exception\InvalidStatisticRequirementsException;
use Rpg\Exception\OutOfRangeStatisticValueException;
use Rpg\Exception\UnsupportedStatisticException;

abstract class AbstractStatistic implements Statistic
{
    protected int $value;
    protected ?int $minimum;
    protected ?int $maximum;

    public function __construct(int $value, ?int $minimum = null, ?int $maximum = null)
    {
        if ($maximum !== null && $minimum !== null && $minimum > $maximum) {
            throw new InvalidStatisticRequirementsException($minimum, $maximum);
        }

        if ($minimum !== null && $value < $minimum) {
            throw new OutOfRangeStatisticValueException($minimum, $value);
        }

        if ($maximum !== null && $value > $maximum) {
            throw new OutOfRangeStatisticValueException($maximum, $value);
        }

        $this->value = $value;
        $this->minimum = $minimum;
        $this->maximum = $maximum;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}