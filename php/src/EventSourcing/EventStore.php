<?php

namespace EventSourcing;

use Ramsey\Uuid\UuidInterface;

interface EventStore
{
    public function append(Stream $stream): void;

    public function load(string $aggregateRootType, UuidInterface $aggregateRootId): Stream;
}