<?php

namespace Rpg;

interface Statistic
{
    public function getName(): string;

    public function getValue(): int;
}