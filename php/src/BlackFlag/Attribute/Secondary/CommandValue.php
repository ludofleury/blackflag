<?php
declare(strict_types=1);

namespace BlackFlag\Attribute\Secondary;


use BlackFlag\Attribute\Primary\Adaptability;
use BlackFlag\Attribute\Primary\Power;
use BlackFlag\Attribute\Secondary;
use Rpg\AbstractTypedStatistic;

class CommandValue extends AbstractTypedStatistic implements Secondary
{
    public function __construct(Adaptability $adaptability)
    {
        $value = $adaptability->getValue() -5;
        parent::__construct( $value);
    }
}