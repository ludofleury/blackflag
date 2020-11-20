<?php

namespace EventSourcing;

abstract class ChildEntity extends Entity
{
    private AggregateRoot $aggregateRoot;

    public function __construct(AggregateRoot $aggregateRoot)
    {
        $aggregateRoot->childEntities[] = $this;
        $this->aggregateRoot = $aggregateRoot;
    }

    protected function apply(Event $event): void
    {
        $this->aggregateRoot->apply($event);
    }
}