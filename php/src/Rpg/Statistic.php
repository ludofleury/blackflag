<?php
declare(strict_types=1);

namespace Rpg;

interface Statistic
{
    public function getName(): string;

    public function getValue(): int;
}