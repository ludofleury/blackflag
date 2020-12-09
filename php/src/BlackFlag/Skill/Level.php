<?php
declare(strict_types=1);

namespace BlackFlag\Skill;

use BlackFlag\Skill\Exception\SkillException;

final class Level
{
    public const MINIMUM = 0;
    public const MAXIMUM = 7;

    private int $value;

    public function __construct(int $value)
    {
        if ($value < self::MINIMUM) {
            throw new SkillException(sprintf('Level %d is too low, minimum %d', $value, self::MINIMUM));
        }

        if ($value > self::MAXIMUM) {
            throw new SkillException(sprintf('Level %d is too high, maximum %d', $value, self::MAXIMUM));
        }

        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function higherThan(self $other): bool
    {
        return $this->value > $other->value;
    }

    public function higherThanOrEqual(self $other): bool
    {
        return $this->value >= $other->value;
    }

    public function lowerThan(self $other): bool
    {
        return $this->value < $other->value;
    }

    public function lowerThanOrEqual(self $other): bool
    {
        return $this->value <= $other->value;
    }
}