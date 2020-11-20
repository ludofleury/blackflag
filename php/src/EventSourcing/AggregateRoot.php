<?php

namespace EventSourcing;

use Ramsey\Uuid\UuidInterface;

abstract class AggregateRoot extends Entity
{
    protected UuidInterface $id;

    /**
     * @var Message[]
     */
    protected array $stream = [];

    protected int $sequence = -1;

    public function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }

    public static function load(UuidInterface $id, Stream $stream): self
    {
        $aggregateRoot = new static($id);

        foreach ($stream as $message) {
            ++$aggregateRoot->sequence;
            $aggregateRoot->handleRecursively($message->getEvent());
        }

        return $aggregateRoot;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getUncommittedEvents(): Stream
    {
        $stream = new Stream(...$this->stream);
        $this->stream = [];

        return $stream;
    }

    protected function apply(Event $event): void
    {
        $this->handleRecursively($event);

        ++$this->sequence;
        $this->stream[] = new Message(
            static::class,
            $this->getId(),
            $this->sequence,
            $event
        );
    }
}
