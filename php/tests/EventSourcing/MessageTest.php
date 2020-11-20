<?php
declare(strict_types=1);

namespace Tests\EventSourcing;

use EventSourcing\Event;
use EventSourcing\Message;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class MessageTest extends TestCase
{
    public function testHasIdentifier(): void
    {
        $event = $this->getMockForAbstractClass(Event::class);
        $message = new Message('\AggregateRoot', Uuid::uuid4(), 0, $event);

        $this->assertInstanceOf(UuidInterface::class, $message->getId());
    }

    public function testHasAggregateRootType(): void
    {
        $event = $this->getMockForAbstractClass(Event::class);
        $message = new Message('\AggregateRoot', Uuid::uuid4(), 0, $event);

        $this->assertEquals('\AggregateRoot', $message->getAggregateRootType());
    }

    public function testHasAggregateRootIdentifier(): void
    {
        $id = Uuid::uuid4();
        $event = $this->getMockForAbstractClass(Event::class);
        $message = new Message('\AggregateRoot', $id, 0, $event);
        $this->assertEquals($id->toString(), $message->getAggregateRootId()->toString());
    }

    public function testHasSequence(): void
    {
        $event = $this->getMockForAbstractClass(Event::class);
        $message = new Message('\AggregateRoot', Uuid::uuid4(), 5, $event);
        $this->assertEquals(5, $message->getSequence());
    }

    public function testRecordsTimestamp(): void
    {
        $event = $this->getMockForAbstractClass(Event::class);

        $time = (new \DateTimeImmutable())->getTimestamp();
        $message = new Message('\AggregateRoot', Uuid::uuid4(), 5, $event);

        $this->assertGreaterThanOrEqual($time-1, $message->getRecordedAt()->getTimestamp());
        $this->assertLessThanOrEqual($time+1, $message->getRecordedAt()->getTimestamp());
    }

    public function testInstantiateEvent(): void
    {
        $event = new MessageTestEvent('OK');
        $message = new Message('\AggregateRoot', Uuid::uuid4(), 0, $event);

        $object = new \ReflectionObject($message);
        $property = $object->getProperty('event');
        $property->setAccessible(true);
        $property->setValue($message, null);

        /** @var MessageTestEvent $eventReturned */
        $eventReturned = $message->getEvent();
        $this->assertInstanceOf(MessageTestEvent::class, $eventReturned);
        $this->assertEquals('OK', $eventReturned->test);
    }
}

class MessageTestEvent implements Event
{
    public string $test;

    public function __construct(string $test)
    {
        $this->test = $test;
    }

    static public function fromArray(array $data): MessageTestEvent
    {
        return new self($data['test']);
    }

    public function toArray(): array
    {
        return ['test' => $this->test];
    }
}
