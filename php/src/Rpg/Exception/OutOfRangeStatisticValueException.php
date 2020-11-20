<?php
declare(strict_types=1);

namespace Rpg\Exception;

use DomainException;

class OutOfRangeStatisticValueException extends DomainException
{
    public function __construct(int $requirement, int $value)
    {
        $lower = '%d is too low: minimum %d';
        $greater = '%d is too high: maximum %d';

        $message = sprintf(
            $value < $requirement ? $lower : $greater,
            $value,
            $requirement
        );

        parent::__construct($message);
    }
}