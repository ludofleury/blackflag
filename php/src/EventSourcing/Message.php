<?php

namespace EventSourcing;

use DateTimeImmutable;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Message
{
    /**
     * @var UuidInterface Message unique identifier
     */
    private UuidInterface $id;
    /**
     * @var string Aggregate root FQCN
     */
    protected string $aggregateRootType;

    protected UuidInterface $aggregateRootId;
    /**
     * @var int Zero-indexed event sequence, unique per aggregate root
     */
    protected int $sequence;

    protected DateTimeImmutable $recordedAt;
    /**
     * @var string Event FQCN
     */
    protected string $eventType;
    /**
     * @var array Event normalized as (json) serializable array
     * @see Event::toArray()
     */
    protected array $data;
    /**
     * @var Event|null internal object cache purpose
     * @see https://github.com/doctrine/orm/issues/7944 Making this class doctrine compliant
     */
    protected ?Event $event = null;

    public function __construct(string $aggregateRootType, UuidInterface $aggregateRootId, $sequence, Event $event)
    {
        $this->id = Uuid::uuid1();
        $this->aggregateRootType = $aggregateRootType;
        $this->aggregateRootId = $aggregateRootId;
        $this->sequence = $sequence;
        $this->recordedAt = new DateTimeImmutable();
        $this->eventType = get_class($event);
        $this->data = $event->toArray();
        $this->event = $event;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getAggregateRootType(): string
    {
        return $this->aggregateRootType;
    }

    public function getAggregateRootId(): UuidInterface
    {
        return $this->aggregateRootId;
    }

    public function getSequence(): int
    {
        return $this->sequence;
    }

    public function getRecordedAt(): DateTimeImmutable
    {
        return $this->recordedAt;
    }

    public function getEvent(): Event
    {
        if ($this->event === null) {
            $this->event = call_user_func_array([$this->eventType, 'fromArray'], [$this->data]);
        }

        return $this->event;
    }
}