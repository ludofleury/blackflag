<?php
declare(strict_types=1);

namespace EventSourcing\Testing;

use EventSourcing\AggregateRoot;
use EventSourcing\Event;
use EventSourcing\Message;
use EventSourcing\Stream;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\TestCase;

/**
 * @psalm-suppress PropertyNotSetInConstructor
 */
class EsTestCase extends TestCase
{
    /**
     * @param array<Event> $expected
     */
    public static function assertUncommittedEvents(array $expected, AggregateRoot $aggregateRoot, string $message = ''): void
    {
        $events = array_map(
            function (Message $message) {
                return $message->getEvent();
            },
            iterator_to_array($aggregateRoot->getUncommittedEvents())
        );

        static::assertThat($events, new IsEqual($expected), $message);
    }
}