<?php
declare(strict_types=1);

namespace BlackFlag\Attribute\Secondary;


use BlackFlag\Attribute\Primary\Adaptability;
use BlackFlag\Attribute\Secondary;
use Rpg\AbstractTypedStatistic;

class Initiative extends AbstractTypedStatistic implements Secondary
{
    public function __construct(Adaptability $adaptability)
    {
        parent::__construct( $adaptability->getValue());
    }
}