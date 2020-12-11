<?php
declare(strict_types=1);

namespace BlackFlag\Skill;

use LogicException;
use InvalidArgumentException;

final class Rule
{
    public const SIMPLE = 'simple';
    public const SPECIALIZED = 'specialized';
    public const SPECIALIZATION = 'specialization';

    public function __construct(
        private string $name,
        private string $type,
        private bool $isProfessional,
    ) {
        if (
            $this->type !== self::SIMPLE &&
            $this->type !== self::SPECIALIZED &&
            $this->type !== self::SPECIALIZATION
        ) {
            throw new InvalidArgumentException(sprintf('Invalid skill type "%s"', $this->type));
        }

        if ($this->isSpecialized() && $this->isProfessional()) {
            throw new LogicException('Skill cannot be specialized and professional');
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isSimple(): bool
    {
        return $this->type === self::SIMPLE;
    }

    public function isSpecialized(): bool
    {
        return $this->type === self::SPECIALIZED;
    }

    public function isSpecialization(): bool
    {
        return $this->type === self::SPECIALIZATION;
    }

    public function isProfessional(): bool
    {
        return $this->isProfessional;
    }
}