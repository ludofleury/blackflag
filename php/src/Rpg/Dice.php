<?php
declare(strict_types=1);

namespace Rpg;

interface Dice
{
    public function getSides(): int;

    public function getResult(): int;
}