<?php
declare(strict_types=1);

namespace Rpg\Exception;

use LogicException;

class InvalidStatisticRequirementsException extends LogicException
{
    public function __construct(int $minimum, int $maximum)
    {
        parent::__construct(
            sprintf(
                'minimum %d cannot be greater than maximum %d',
                $minimum,
                $maximum
            )
        );
    }
}