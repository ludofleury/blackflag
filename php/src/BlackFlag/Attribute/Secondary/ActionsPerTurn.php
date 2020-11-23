<?php
declare(strict_types=1);

namespace BlackFlag\Attribute\Secondary;


use BlackFlag\Attribute\Primary\Power;
use BlackFlag\Attribute\Secondary;
use Rpg\AbstractTypedStatistic;

class ActionsPerTurn extends AbstractTypedStatistic implements Secondary
{
    public function __construct()
    {
        parent::__construct( 2);
    }
}