<?php
declare(strict_types=1);

namespace BlackFlag\Resolution;

use BlackFlag\Attribute\Primary\Adaptability;
use BlackFlag\Attribute\Primary\Charisma;
use BlackFlag\Attribute\Primary\Constitution;
use BlackFlag\Attribute\Primary\Dexterity;
use BlackFlag\Attribute\Primary\Expression;
use BlackFlag\Attribute\Primary\Knowledge;
use BlackFlag\Attribute\Primary\Perception;
use BlackFlag\Attribute\Primary\Power;
use DomainException;

class PrimaryAttributeName
{
    /** @var array<string, class-string>  */
    private static array $rules = [
        Adaptability::name => Adaptability::class,
        Charisma::name => Charisma::class,
        Constitution::name => Constitution::class,
        Dexterity::name => Dexterity::class,
        Expression::name => Expression::class,
        Knowledge::name => Knowledge::class,
        Perception::name => Perception::class,
        Power::name => Power::class
    ];

    private string $value;

    public function __construct(string $name)
    {
        if (!isset(self::$rules[$name])) {
            throw new DomainException(sprintf('Unknown primary attribute "%s"', $name));
        }

        $this->value = $name;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}