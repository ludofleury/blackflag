<?php

namespace Rpg\Exception;

use DomainException;

class OutOfRangeStatisticValueException extends DomainException
{
    public function __construct(string $type, string $name, int $requirement, int $value)
    {
        $lower = '%s "%s" (%d) is under the minimum requirement of %d';
        $greater = '%s "%s" (%d) exceed the maximum requirement of %d';

        $message = sprintf(
            $value < $requirement ? $lower : $greater,
            ucfirst($type),
            $name,
            $value,
            $requirement
        );

        parent::__construct($message);
    }
}