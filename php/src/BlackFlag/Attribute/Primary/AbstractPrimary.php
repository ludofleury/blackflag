<?php
declare(strict_types=1);

namespace BlackFlag\Attribute\Primary;

use BlackFlag\Attribute\Primary;
use Rpg\AbstractStatistic;
use Rpg\Reference\Book;

#[Book(ISBN: '978-2-36328-252-1', page: 14)]
abstract class AbstractPrimary extends AbstractStatistic implements Primary
{
    final public function __construct(int $value)
    {
        parent::__construct($value, 2, 9);
    }
}