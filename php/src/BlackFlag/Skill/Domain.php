<?php
declare(strict_types=1);

namespace BlackFlag\Skill;

use DomainException;
use LogicException;

class Domain  implements Knowledge, Technical, Maritime, Physical, Social, Combat
{
    private string $name;
    private ?string $specialization;

    public function __construct(string $name, ?string $specialization = null)
    {
        if (!Registry::hasDomain($name)) {
            !Registry::has($name)
                ? throw new DomainException(sprintf('Unknown skill domain "%s"', $name))
                : throw new LogicException(sprintf('"%s" is a specialization, instantiate with BlackFlag\\Skill("[main skill]", "%s", ...) instead', $name, $name))
            ;
        }

        $this->name = $name;
        $this->specialization = $specialization;
    }

    public function __toString(): string
    {
        return $this->specialization !== null
            ? sprintf('%s: %s', $this->name, $this->specialization)
            : $this->name
        ;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isSpecialized(): bool
    {
        return $this->specialization !== null;
    }

    public function getSpecialization(): string
    {
        if ($this->specialization === null) {
            throw new LogicException(sprintf('Skill %s is not a specialization', $this->name));
        }

        return $this->specialization;
    }
}