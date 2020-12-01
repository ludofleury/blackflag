<?php
declare(strict_types=1);

namespace BlackFlag\Resolution\Dice;

use BlackFlag\Resolution\Dice;
use DomainException;

/**
 * Official Black Flag "action" dices factory
 *
 * @method static Dice D10()
 * @method static Dice D12()
 * @method static Dice D20()
 * @method static Dice D100()
 */
trait Factory
{
    /**
     * @param array<mixed> $arguments Should be empty
     * @example Dice::D20()
     */
    public static function __callStatic(string $type, array $arguments): Dice
    {
        return new Dice(
            match ($type) {
                'D10' => 10,
                'D12' => 12,
                'D20' => 20,
                'D100' => 100,
                default => throw new DomainException(sprintf('%s is not an official Black Flag dice', $type))
            }
        );
    }
}