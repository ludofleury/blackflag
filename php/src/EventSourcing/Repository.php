<?php

namespace EventSourcing;

use EventSourcing\Exception\AggregateRootNotFoundException;
use EventSourcing\Exception\InvalidAggregateRootTypeException;
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
        if (!class_exists($this->aggregateRootType)) {
            throw new InvalidAggregateRootTypeException(
                sprintf(
                    'Aggregate root type "%s" does not exists, check your FQCN and your autoload configuration',
                    $this->aggregateRootType
                )
            );
        }

        $parentClasses = class_parents($this->aggregateRootType);
        if (false === $parentClasses || !in_array(AggregateRoot::class, $parentClasses)) {
            throw new InvalidAggregateRootTypeException(
                sprintf(
                    'Class "%s" does not implement "%s": inheritance is mandatory',
                    $this->aggregateRootType,
                    AggregateRoot::class
                )
            );
        }

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

        /** @var callable */
        $aggregateRootLoadMethod = $this->aggregateRootType.'::load';

        return call_user_func_array($aggregateRootLoadMethod, [$aggregateRootId, $stream]);
    }
}