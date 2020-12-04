<?php
declare(strict_types=1);

namespace App\EventSourcing;

use EventSourcing\EventBus as EventBusInterface;
use EventSourcing\Stream;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DispatchAfterCurrentBusStamp;

final class EventBus implements EventBusInterface
{
    private MessageBusInterface $eventBus;

    public function __construct(MessageBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function dispatch(Stream $stream): void
    {
        foreach ($stream as $message) {
            $this->eventBus->dispatch(
                (new Envelope($message))->with(new DispatchAfterCurrentBusStamp())
            );
        }
    }
}