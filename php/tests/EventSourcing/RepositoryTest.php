<?php
declare(strict_types=1);

namespace Tests\EventSourcing;

use EventSourcing\AggregateRoot;
use EventSourcing\Event;
use EventSourcing\EventBus;
use EventSourcing\EventStore;
use EventSourcing\Exception\AggregateRootNotFoundException;
use EventSourcing\Exception\InvalidAggregateRootTypeException;
use EventSourcing\Message;
use EventSourcing\Repository;
use EventSourcing\Stream;
use EventSourcing\Testing\EsTestCase;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class RepositoryTest extends EsTestCase
{
    public function testRejectsNonExistentClass(): void
    {
        $this->expectException(InvalidAggregateRootTypeException::class);
        $this->expectExceptionMessage('Aggregate root type "\Unknown\Class" does not exists, check your FQCN and your autoload configuration');

        $es = $this->createMock(EventStore::class);
        $eb = $this->createMock(EventBus::class);

        new class('\Unknown\Class', $es, $eb) extends Repository {};
    }

    public function testHandlesOnlyAggregateRoot(): void
    {
        $notArClass = get_class(new class() {});

        $this->expectException(InvalidAggregateRootTypeException::class);
        $this->expectExceptionMessage(sprintf('Class "%s" is not an aggregate root, must extends "%s"', $notArClass, AggregateRoot::class));

        $es = $this->createMock(EventStore::class);
        $eb = $this->createMock(EventBus::class);

        new class($notArClass, $es, $eb) extends Repository {};
    }

    public function testSavesByAppendingAndDispatchingUncommittedEventStream(): void
    {
        $ar = new class(Uuid::uuid4()) extends AggregateRoot
        {
            public bool $getUncommittedEventCalled = false;

            public function test(): void
            {
                $this->apply(new RepositoryTestEvent('1test'));
                $this->apply(new RepositoryTestEvent('2test'));
            }

            public function debugStream(): Stream
            {
                return new Stream(...$this->stream);
            }

            public function getUncommittedEvents(): Stream
            {
                $this->getUncommittedEventCalled = true;
                return parent::getUncommittedEvents(); // TODO: Change the autogenerated stub
            }
        };

        $es = new class() implements EventStore
        {
            public Stream $streamAppended;
            public function append(Stream $stream): void
            {
                $this->streamAppended = $stream;
            }
            public function load(string $aggregateRootType, UuidInterface $aggregateRootId): Stream
            {
                return new Stream();
            }
        };

        $eb = new class() implements EventBus
        {
            public Stream $streamDispatched;
            public function dispatch(Stream $stream): void
            {
                $this->streamDispatched = $stream;
            }
        };

        $repository = new class(get_class($ar), $es, $eb) extends Repository {};

        $ar->test();

        $appliedStream = $ar->debugStream();

        $repository->save($ar);

        $appliedMessages = [];
        foreach ($appliedStream as $message) {
            $appliedMessages[] = $message;
        }

        $appendedMessages = [];
        foreach ($es->streamAppended as $message) {
            $appendedMessages[] = $message;
        }

        $dispatchedMessage = [];
        foreach ($eb->streamDispatched as $message) {
            $dispatchedMessage[] = $message;
        }

        $this->assertTrue($ar->getUncommittedEventCalled);
        $this->assertEquals($appliedMessages, $appendedMessages);
        $this->assertEquals($appliedMessages, $dispatchedMessage);
    }

    public function testLoadsByInstantiatingAndInitializingAnAggregateRoot(): void
    {
        $ar = new class(Uuid::uuid4()) extends AggregateRoot
        {
            public string $test = '';

            protected function applyRepositoryTestEvent(RepositoryTestEvent $event): void
            {
                $this->test .= $event->id;
            }
        };

        $arClass = get_class($ar);
        $arId = Uuid::uuid4();
        $loadedStream = new Stream(
            new Message($arClass,$arId, 0, new RepositoryTestEvent('1test')),
            new Message($arClass,$arId, 1, new RepositoryTestEvent('2test'))
        );

        $es = $this->createMock(EventStore::class);
        $es->expects($this->once())
            ->method('load')
            ->with($this->equalTo($arClass), $this->equalTo($arId))
            ->willReturn($loadedStream)
        ;

        $eb = $this->createMock(EventBus::class);

        $repository = new class($arClass, $es, $eb) extends Repository {};
        $loadedAr = $repository->load($arId);

        /** @phpstan-ignore-next-line */
        $this->assertEquals('1test2test', $loadedAr->test);
    }

    public function testCannotLoadFromAnEmptyStream(): void
    {
        $ar = new class(Uuid::uuid4()) extends AggregateRoot
        {
        };
        $arClass = get_class($ar);
        $arId = Uuid::uuid4();

        $this->expectException(AggregateRootNotFoundException::class);
        $this->expectExceptionMessage(sprintf('No persisted event for "%s": "%s"', $arClass, $arId));

        $es = $this->createMock(EventStore::class);
        $es->expects($this->once())
            ->method('load')
            ->with($this->equalTo($arClass), $this->equalTo($arId))
            ->willReturn(new Stream())
        ;

        $eb = $this->createMock(EventBus::class);

        $repository = new class($arClass, $es, $eb) extends Repository {};
        $repository->load($arId);
    }
}

class RepositoryTestEvent implements Event
{
    public string $id;

    public function __construct(string $id)
    {
        $this->id = $id;
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
