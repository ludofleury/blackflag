<?php
declare(strict_Types=1);

namespace BlackFlag;

use BlackFlag\Skill\Exception\SkillException;
use BlackFlag\Skill\Factory;
use BlackFlag\Skill\Registry;
use DomainException;
use LogicException;
use Rpg\Reference as RPG;

/**
 * Immutable value object representing a Black Flag skill and its level
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

    private string $name;
    private ?string $specialization;
    private int $level;
    private bool $isProfessional;

    public function __construct(string $name, ?string $specialization, int $level, bool $isProfessional)
    {
        if (!Registry::hasMainSkill($name)) {
            !Registry::hasSkill($name)
                ? throw new DomainException(sprintf('Unknown skill "%s"', $name))
                : throw new LogicException(sprintf('"%s" is a specialization, instantiate with BlackFlag\\Skill("[main skill]", "%s", ...) instead', $name, $name))
            ;
        }

        if ($level < 0) {
            throw new SkillException(sprintf('"%s": %d is too low, minimum %d', $name, $level, 0));
        }

        if ($level > 7) {
            throw new SkillException(sprintf('"%s": %d is too high, maximum %d', $name, $level, 7));
        }

        $this->name = $name;
        $this->specialization = $specialization;
        $this->level = $level;
        $this->isProfessional = $isProfessional;
    }

    public function isDeveloped(): bool
    {
        return $this->level > 0;
    }

    public function isSpecialized(): bool
    {
        return $this->specialization !== null;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSpecialization(): ?string
    {
        return $this->specialization;
    }

    public function getLevel(): int
    {
        return $this->level;
    }

    public function isProfessional(): bool
    {
        return $this->isProfessional;
    }

    public function equals(self $other): bool
    {
        return $this->level === $other->level;
    }

    public function higherThan(self $other): bool
    {
        return $this->level > $other->level;
    }

    public function higherThanOrEqual(self $other): bool
    {
        return $this->level >= $other->level;
    }

    public function lowerThan(self $other): bool
    {
        return $this->level < $other->level;
    }

    public function lowerThanOrEqual(self $other): bool
    {
        return $this->level <= $other->level;
    }
}