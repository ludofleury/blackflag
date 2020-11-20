<?php

namespace EventSourcing\Testing;

use EventSourcing\AggregateRoot;
use EventSourcing\Message;
use PHPUnit\Framework\Constraint\IsEqual;
use PHPUnit\Framework\TestCase;

class EsTestCase extends TestCase
{
    public static function assertHistory(AggregateRoot $aggregateRoot, $expected, string $message = ''): void
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