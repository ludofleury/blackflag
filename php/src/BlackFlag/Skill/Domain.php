<?php
declare(strict_types=1);

namespace BlackFlag\Skill;

use BlackFlag\Skill\Domain\Validator;

use DomainException;
use LogicException;

class Domain
{
    use Validator;

    private string $name;
    private ?string $specialization;

    public function __construct(string $name, ?string $specialization = null)
    {
        if (!$this->supports($name)) {
            throw new DomainException(sprintf('Unknown skill domain "%s"', $name));
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