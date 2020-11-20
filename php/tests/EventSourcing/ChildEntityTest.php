<?php
declare(strict_types=1);

namespace Tests\EventSourcing;

use EventSourcing\AggregateRoot;
use EventSourcing\ChildEntity;
use EventSourcing\Event;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ChildEntityTest extends TestCase
{
    public function testDelegatesEventApplicationToAggregateRoot(): void
    {
        $ar = new class(Uuid::uuid4()) extends AggregateRoot
        {
            protected function applyDelegatedDummyEvent(DelegatedDummyEvent $event): void
            {
                $event->witness .= '1ar';
            }
        };

        $child = new class($ar) extends ChildEntity
        {
            public function test(DelegatedDummyEvent $toApply): void
            {
                $this->apply($toApply);
            }

            protected function applyDelegatedDummyEvent(DelegatedDummyEvent $event): void
            {
                $event->witness .= '2child';
            }
        };


        $event = new DelegatedDummyEvent();
        $child->test($event);

        $this->assertEquals('1ar2child', $event->witness);
    }
}

class DelegatedDummyEvent implements Event
{
    public string $witness = '';

    static public function fromArray(array $data): Event
    {
        return new self();
    }

    public function toArray(): array
    {
        return [];
    }
}