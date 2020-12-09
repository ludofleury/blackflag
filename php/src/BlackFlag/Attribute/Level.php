<?php
declare(strict_types=1);

namespace BlackFlag\Attribute;

use BlackFlag\Attribute\Exception\InvalidLevel;
use Rpg\Reference as RPG;

#[RPG\Book(ISBN: '978-2-36328-252-1', page: 14)]
#[RPG\Term(lang: 'fr', text: 'niveau de caractéristique')]
#[RPG\Term(lang: 'fr', text: 'valeur de métier')]
#[RPG\Term(lang: 'fr', text: 'valeur de commandement')]
final class Level
{
    public const MINIMUM = 2;
    public const MAXIMUM = 8;

    private int $value;

    public function __construct(int $value)
    {
        if ($value < self::MINIMUM) {
            throw new InvalidLevel(sprintf('Level %d is too low, minimum %d', $value, self::MINIMUM));
        }

        if ($value > self::MAXIMUM) {
            throw new InvalidLevel(sprintf('Level %d is too high, maximum %d', $value, self::MAXIMUM));
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