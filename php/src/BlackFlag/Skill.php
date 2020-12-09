<?php
declare(strict_Types=1);

namespace BlackFlag;

use BlackFlag\Skill\Factory;
use BlackFlag\Skill\Level;
use BlackFlag\Skill\Domain;
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
    use Factory;

    private Domain $domain;
    private Level $level;
    private bool $isProfessional;

    public function __construct(Domain $domain, int $level, bool $isProfessional)
    {
        $this->domain = $domain;
        $this->level = new Level($level);
        $this->isProfessional = $isProfessional;
    }

    public function isDeveloped(): bool
    {
        return $this->level->getValue() > 0;
    }

    public function isSpecialized(): bool
    {
        return $this->domain->isSpecialized();
    }

    public function getName(): string
    {
        return $this->domain->getName();
    }

    public function getSpecialization(): ?string
    {
        return $this->domain->getSpecialization();
    }

    public function getLevel(): int
    {
        return $this->level->getValue();
    }

    public function isProfessional(): bool
    {
        return $this->isProfessional;
    }

    public function equals(self $other): bool
    {
        return $this->level->equals($other->level);
    }

    public function higherThan(self $other): bool
    {
        return $this->level->higherThan($other->level);
    }

    public function higherThanOrEqual(self $other): bool
    {
        return $this->level->higherThanOrEqual($other->level);
    }

    public function lowerThan(self $other): bool
    {
        return $this->level->lowerThan($other->level);
    }

    public function lowerThanOrEqual(self $other): bool
    {
        return $this->level->lowerThanOrEqual($other->level);
    }
}