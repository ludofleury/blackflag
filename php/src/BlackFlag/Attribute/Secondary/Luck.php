<?php
declare(strict_types=1);

namespace BlackFlag\Attribute\Secondary;


use BlackFlag\Attribute\Primary\Power;
use BlackFlag\Attribute\Secondary;
use Rpg\AbstractTypedStatistic;

class Luck extends AbstractTypedStatistic implements Secondary
{
    public function __construct(Power $power)
    {
        $value = $power->getValue() -5;
        parent::__construct( $value);
    }
}