<?php
declare(strict_types=1);

namespace Rpg\Dice;

interface RandomGenerator
{
    public function generate(int $max): int;
}