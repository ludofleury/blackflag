<?php

namespace Rpg\Exception;

use LogicException;

class InvalidStatisticRequirementsException extends LogicException
{
    public function __construct(string $type, string $name, int $minimum, int $maximum)
    {
        parent::__construct(
            sprintf(
                '%s %s requirements are invalid: minimum (%d) cannot be greater than maximum (%d)',
                $type,
                $name,
                $minimum,
                $maximum
            )
        );
    }
}