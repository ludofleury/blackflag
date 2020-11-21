<?php
declare(strict_types=1);

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
        /** @psalm-suppress InaccessibleMethod */
        $this->aggregateRoot->apply($event);
    }
}