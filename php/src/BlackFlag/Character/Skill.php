<?php
declare(strict_Types=1);

namespace BlackFlag;

use BlackFlag\Skill\Level;
use BlackFlag\Skill\Domain;
use DomainException;
use InvalidArgumentException;
use Rpg\Reference as RPG;

/**
 * Immutable value object representing a Black Flag skill: domain, level & professional qualification
 *
 * - Supports:
 * - only registered main/macro skill
 * - any custom specializations
 */
#[RPG\Book(ISBN: '978-2-36328-252-1', page: 16)]
#[RPG\Term(lang: 'fr', text: 'compétence')]
final class Skill
{
    private string $name;
    private Level $level;
    private bool $isProfessional;

    public function __construct(string $name, int $level, bool $isProfessional)
    {
        if (strlen(trim($name, ' ')) === 0) {
            throw new InvalidArgumentException('Skill name should not be blank');
        }

        $this->name = $name;
        $this->level = new Level($level);
        $this->isProfessional = $isProfessional;
    }

    public function isDeveloped(): bool
    {
        return $this->level->getValue() > 0;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLevel(): int
    {
        return $this->level->getValue();
    }

    public function isProfessional(): bool
    {
        return $this->isProfessional;
    }
}