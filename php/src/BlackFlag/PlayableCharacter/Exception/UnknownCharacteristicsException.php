<?php
declare(strict_types=1);

namespace BlackFlag\PlayableCharacter\Exception;

use DomainException;

class UnknownCharacteristicsException extends DomainException
{
    public function __construct(string $name)
    {
        parent::__construct(sprintf('Unsupported attribute "%s"', $name));
    }
}