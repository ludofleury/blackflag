<?php
declare(strict_types=1);

namespace EventSourcing;

use EventSourcing\Exception\ChronologicalException;
use EventSourcing\Exception\MessageMismatchException;
use Ramsey\Uuid\UuidInterface;

abstract class AggregateRoot extends Entity
{
    protected UuidInterface $id;

    /**
     * @var Message[]
     */
    protected array $stream = [];

    protected int $sequence = -1;

    final public function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }

    public static function load(UuidInterface $id, Stream $stream): static
    {

        $aggregateRoot = new static($id);

        foreach ($stream as $message) {
            ++$aggregateRoot->sequence;

            if ($message->getAggregateRootType() !== static::class || !$message->getAggregateRootId()->equals($aggregateRoot->id)) {
                throw new MessageMismatchException(
                    sprintf(
                        'Message (AR: "%s" AR_ID: "%s" , id: "%s", seq: "%d" event: "%s") is not addressed to this AR ("%s": "%s")',
                        $message->getAggregateRootType(),
                        $message->getAggregateRootId(),
                        $message->getId()->toString(),
                        $message->getSequence(),
                        $message->getEvent()::class,
                        $aggregateRoot::class,
                        $aggregateRoot->getAggregateRootId()->toString()
                    )
                );
            }


            if ($message->getSequence() !== $aggregateRoot->sequence) {
                throw new ChronologicalException(
                    sprintf(
                        'Message sequence (%d) not synchronized with AR sequence (%d)',
                        $message->getSequence(),
                        $aggregateRoot->sequence
                    )
                );
            }

            $aggregateRoot->handleRecursively($message->getEvent());
        }

        return $aggregateRoot;
    }

    public function getAggregateRootId(): UuidInterface
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
            $this->id,
            $this->sequence,
            $event
        );
    }
}
