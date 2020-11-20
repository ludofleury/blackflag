<?php

namespace EventSourcing;

use EventSourcing\Exception\AggregateRootNotFoundException;
use Ramsey\Uuid\UuidInterface;

abstract class Repository
{
    private string $aggregateRootType;
    private EventStore $eventStore;
    private EventBus $eventBus;

    public function __construct(
        string $aggregateRootType,
        EventStore $eventStore,
        EventBus $eventBus
    ) {
        $this->aggregateRootType = $aggregateRootType;
        $this->eventStore = $eventStore;
        $this->eventBus = $eventBus;
    }

    public function save(AggregateRoot $aggregateRoot): void
    {
        $stream = $aggregateRoot->getUncommittedEvents();
        $this->eventStore->append($stream);
        $this->eventBus->dispatch($stream);
    }

    public function load(UuidInterface $aggregateRootId): AggregateRoot
    {
        $stream = $this->eventStore->load($this->aggregateRootType, $aggregateRootId);

        if (empty($stream)) {
            throw new AggregateRootNotFoundException(sprintf('No persisted event for "%s": "%s"', $this->aggregateRootType, $aggregateRootId));
        }

        return call_user_func_array([$this->aggregateRootType, 'load'], [$aggregateRootId, $stream]);
    }
}