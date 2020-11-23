<?php
declare(strict_types=1);

namespace BlackFlag\Attribute\Primary;

use BlackFlag\Attribute\Primary;
use Rpg\AbstractStatistic;

final class Constitution extends AbstractStatistic implements Primary
{
    public const name = 'constitution';

    public function __construct(int $value, int $minimum = 5, int $maximum = 8)
    {
        parent::__construct($value, $minimum, $maximum);
    }

    public function getName(): string
    {
        return self::name;
    }
}