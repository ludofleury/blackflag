<?php
declare(strict_types=1);

namespace Tests\EventSourcing;

use EventSourcing\AggregateRoot;
use EventSourcing\ChildEntity;
use EventSourcing\Event;
use EventSourcing\Exception\ChronologicalException;
use EventSourcing\Exception\MessageMismatchException;
use EventSourcing\Message;
use EventSourcing\Stream;
use EventSourcing\Testing\EsTestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class AggregateRootTest extends EsTestCase
{
    public function testHasUniqueIdentifier(): void
    {
        $id = Uuid::fromString('ab0ca3fa-2862-11eb-bdd8-0242ac170003');
        $ar = new class($id) extends AggregateRoot {};

        $this->assertEquals(
            $id,
            $ar->getId()
        );
    }

    public function testAppliesEvent(): void
    {
        $id = Uuid::fromString('ab0ca3fa-2862-11eb-bdd8-0242ac170003');

        $ar = new class($id) extends AggregateRoot {
            public bool $hasBeenApplied = false;

            public function dummy(): void {
                $this->apply(new DummyEvent('test'));
            }
            protected function applyDummyEvent(DummyEvent $event): void
            {
                $this->hasBeenApplied = true;
            }
        };

        $this->assertFalse($ar->hasBeenApplied);
        $ar->dummy();
        $this->assertTrue($ar->hasBeenApplied);
    }

    public function testRecordsEventsInAMessageStream(): void
    {
        $id = Uuid::fromString('ab0ca3fa-2862-11eb-bdd8-0242ac170003');
        $ar = new class($id) extends AggregateRoot {
            public function dummy(string $id): void
            {
                $this->apply(new DummyEvent($id));
            }
        };
        $ar->dummy('test1');
        $ar->dummy('test2');
        $ar->dummy('test3');

        $stream = $ar->getUncommittedEvents();
        $this->assertInstanceOf(
            Stream::class,
            $stream
        );

        $events = array_map(
            function (Message $message) { return $message->getEvent();},
            iterator_to_array($stream)
        );

        $this->assertEquals(
            [new DummyEvent('test1'), new DummyEvent('test2'), new DummyEvent('test3')],
            $events
        );
    }

    public function testFlushesUncommittedEventsAfterProvidingStream(): void
    {
        $id = Uuid::fromString('ab0ca3fa-2862-11eb-bdd8-0242ac170003');
        $ar = new class($id) extends AggregateRoot {
            public function dummy(string $id): void
            {
                $this->apply(new DummyEvent($id));
            }
        };
        $ar->dummy('test1');

        $stream = $ar->getUncommittedEvents();
        $events = array_map(
            function (Message $message) { return $message->getEvent();},
            iterator_to_array($stream)
        );
        $this->assertEquals(
            [new DummyEvent('test1')],
            $events
        );

        $ar->dummy('test2');
        $stream = $ar->getUncommittedEvents();
        $events = array_map(
            function (Message $message) { return $message->getEvent();},
            iterator_to_array($stream)
        );
        $this->assertEquals(
            [new DummyEvent('test2')],
            $events
        );
    }

    public function testAssignsZeroBasedSequencesToEventMessages(): void
    {
        $id = Uuid::fromString('ab0ca3fa-2862-11eb-bdd8-0242ac170003');
        $ar = new class($id) extends AggregateRoot {
            public function dummy(string $id): void
            {
                $this->apply(new DummyEvent($id));
            }
        };
        $ar->dummy('test1');
        $ar->dummy('test2');
        $ar->dummy('test3');

        $stream = $ar->getUncommittedEvents();

        $previousSequence = -1;
        foreach ($stream as $message) {
            $this->assertEquals(
                ++$previousSequence,
                $message->getSequence()
            );
        }
    }

    public function testInitializesItselfFromAStream(): void
    {
        $id = Uuid::fromString('ab0ca3fa-2862-11eb-bdd8-0242ac170003');
        $ar = new StubAggregateRoot($id);
        $ar->dummy('test0');
        $ar->dummy('test1');
        $ar->dummy('test2');

        $stream = $ar->getUncommittedEvents();

        unset($ar);
        $ar = StubAggregateRoot::load($id, $stream);
        $this->assertInstanceOf(StubAggregateRoot::class, $ar);
        $this->assertEquals(
            'test0test1test2',
            $ar->getWitness()
        );

        // Should have an empty stream of events
        $stream2 = $ar->getUncommittedEvents();
        $this->assertCount(
            0,
            $stream2
        );

        // Should sequence new message based on the latest message loaded
        $ar->dummy('test3');
        $stream3 = $ar->getUncommittedEvents();
        foreach ($stream3 as $message) {
            $this->assertEquals(
                3,
                $message->getSequence()
            );
            break;
        }
    }

    public function testCannotLoadMessageFromDifferentAggregateRootType(): void
    {
        $this->expectException(MessageMismatchException::class);

        $arId = Uuid::fromString('ab0ca3fa-2862-11eb-bdd8-0242ac170003');

        $wrongStream = new Stream( new Message('\\Toto', $arId, 0, new DummyEvent('test')));
        StubAggregateRoot::load($arId,$wrongStream);
    }

    public function testCannotLoadMessageForOtherAggregateRootId(): void
    {
        $this->expectException(MessageMismatchException::class);

        $wrongStreamArId = Uuid::fromString('ab0ca3fa-2862-11eb-bdd8-0242ac170003');
        $wrongStream = new Stream( new Message(StubAggregateRoot::class, $wrongStreamArId, 0, new DummyEvent('test')));

        StubAggregateRoot::load(Uuid::fromString('c78eaa82-59dd-4a45-a169-c9d4c0ae5a0e'),$wrongStream);
    }

    public function testSynchronizesSequenceWhenLoading(): void
    {
        $this->expectException(ChronologicalException::class);
        $this->expectExceptionMessage('Message sequence (2) not synchronized with AR sequence (1)');

        $arId = Uuid::fromString('ab0ca3fa-2862-11eb-bdd8-0242ac170003');
        $asyncStream = new Stream(
            new Message(StubAggregateRoot::class, $arId, 0, new DummyEvent('test')),
            new Message(StubAggregateRoot::class, $arId, 2, new DummyEvent('test'))
        );

        StubAggregateRoot::load($arId,$asyncStream);
    }

    public function testPropagatesEventsToItsChildEntities(): void
    {
        $id = Uuid::fromString('ab0ca3fa-2862-11eb-bdd8-0242ac170003');
        $ar = new class($id) extends AggregateRoot
        {
            public function dummy(string $id): void
            {
                $this->apply(new DummyEvent($id));
            }
        };

        $child = new class($ar) extends ChildEntity
        {
            public string $witness = '';
            protected function applyDummyEvent(DummyEvent $event): void
            {
                $this->witness .= $event->getId();
            }
        };

        $this->assertEquals('', $child->witness);
        $ar->dummy('test1');
        $this->assertEquals('test1', $child->witness);
        $ar->dummy('test2');
        $this->assertEquals('test1test2', $child->witness);
    }
}

class DummyEvent implements Event
{
    private string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    static public function fromArray(array $data): Event
    {
        return new self($data['id']);
    }

    public function toArray(): array
    {
        return ['id' => $this->id];
    }
}

class StubAggregateRoot extends AggregateRoot
{
    private string $witness = '';

    public function dummy(string $id): void
    {
        $this->apply(new DummyEvent($id));
    }

    public function applyDummyEvent(DummyEvent $event): void
    {
        $this->witness .= $event->getId();
    }

    public function getWitness(): string
    {
        return $this->witness;
    }
}