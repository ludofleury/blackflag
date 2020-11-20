<?php

namespace Tests\EventSourcing;

use EventSourcing\AggregateRoot;
use EventSourcing\ChildEntity;
use EventSourcing\Event;
use EventSourcing\Message;
use EventSourcing\Stream;
use EventSourcing\Testing\EsTestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class AggregateRootTest extends EsTestCase
{

    public function testHasUniqueIdentifier()
    {
        $id = Uuid::fromString('ab0ca3fa-2862-11eb-bdd8-0242ac170003');
        $ar = new class($id) extends AggregateRoot {};

        $this->assertEquals(
            $id,
            $ar->getId()
        );
    }

    public function testAppliesEvent()
    {
        $id = Uuid::fromString('ab0ca3fa-2862-11eb-bdd8-0242ac170003');

        $ar = new class($id) extends AggregateRoot {
            public bool $hasBeenApplied = false;

            public function dummy() {
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

    public function testRecordsEventsInAMessageStream()
    {
        $id = Uuid::fromString('ab0ca3fa-2862-11eb-bdd8-0242ac170003');
        $ar = new class($id) extends AggregateRoot {
            public function dummy($id) {
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

    public function testFlushesUncommittedEventsAfterProvidingStream()
    {
        $id = Uuid::fromString('ab0ca3fa-2862-11eb-bdd8-0242ac170003');
        $ar = new class($id) extends AggregateRoot {
            public function dummy($id) {
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

    public function testAssignsZeroBasedSequencesToEventMessages()
    {
        $id = Uuid::fromString('ab0ca3fa-2862-11eb-bdd8-0242ac170003');
        $ar = new class($id) extends AggregateRoot {
            public function dummy($id) {
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

    public function testInitializesItselfFromAStream()
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

    public function testPropagatesEventsToItsChildEntities()
    {
        $id = Uuid::fromString('ab0ca3fa-2862-11eb-bdd8-0242ac170003');
        $ar = new class($id) extends AggregateRoot {
            public StubChildEntity $child;
            public function __construct(UuidInterface $id)
            {
                parent::__construct($id);
                $this->child = new StubChildEntity($this);
            }

            public function dummy($id) {
                $this->apply(new DummyEvent($id));
            }

            public function getChildDummyEventCalls(): int
            {
                return $this->child->getDummyEventCalls();
            }
        };

        $this->assertEquals(0, $ar->getChildDummyEventCalls());
        $ar->dummy('test1');
        $this->assertEquals(1, $ar->getChildDummyEventCalls());
        $ar->dummy('test2');
        $this->assertEquals(2, $ar->getChildDummyEventCalls());
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

    public function dummy($id) {
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

class StubChildEntity extends ChildEntity
{
    private int $dummyEventCalls = 0;

    public function getDummyEventCalls(): int
    {
        return $this->dummyEventCalls;
    }

    protected function applyDummyEvent(DummyEvent $event): void
    {
        $this->dummyEventCalls++;
    }
}