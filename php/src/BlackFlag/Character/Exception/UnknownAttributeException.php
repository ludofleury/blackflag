<?php
declare(strict_types=1);

namespace BlackFlag\Character\Exception;

use DomainException;

class UnknownAttributeException extends DomainException
{
    public function __construct(string $name)
    {
        parent::__construct(sprintf('Unsupported attribute "%s"', $name));
    }
}