<?php
declare(strict_types=1);

namespace Rpg\Dice;

use DomainException;
use Rpg\Dice;
use Rpg\Dice\Generator\PhpCSPRNGenerator;

/**
 * Represent an immutable dice, immediately casted/rolled
 * Cryptographically secure pseudo-random integer generator
 *
 * Accept physical dice and non-physical ones, example: 3 sides
 */
class AnyDice implements Dice
{
    private int $sides;

    private int $result;

    public function __construct(int $sides, RandomGenerator $generator = null)
    {
        if ($sides < 2) {
            throw new DomainException('Dice must have at least 2 sides');
        }
        $this->sides = $sides;
        $this->result = $generator === null ? (new PhpCSPRNGenerator())->generate($sides) : $generator->generate($sides);
    }

    public function getSides(): int
    {
        return $this->sides;
    }

    public function getResult(): int
    {
        return $this->result;
    }
}