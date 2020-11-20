<?php

namespace EventSourcing;

use ArrayIterator;
use Countable;
use IteratorIterator;

final class Stream extends IteratorIterator implements Countable
{
    public function __construct(Message ...$messages)
    {
        parent::__construct(new ArrayIterator($messages));
    }

    public function current() : Message
    {
        return parent::current();
    }

    public function count() : int
    {
        return $this->getInnerIterator()->count();
    }
}