<?php
declare(strict_types=1);

namespace BlackFlag;

use BlackFlag\Attribute\Special\Occupation;
use BlackFlag\Attribute\Special\Rank;
use BlackFlag\Attribute\Characteristic;
use BlackFlag\Attribute\Level;
use Rpg\Reference as RPG;

final class Attribute
{
    private Characteristic|Occupation|Rank $type;

    private Level $level;

    public function __construct(Characteristic|Occupation|Rank $type, int $level)
    {
        $this->type = $type;
        $this->level = new Level($level);
    }

    public function getName(): string
    {
        return $this->type->getName();
    }

    public function getLevel(): int
    {
        return $this->level->getValue();
    }
}