<?php
declare(strict_types=1);

namespace Rpg\Dice\Generator;

class PhpCSPRNGenerator
{
    public function generate(int $max): int
    {
        return \random_int(1, $max);
    }
}