<?php

namespace App\EventSourcing;

use EventSourcing\EventBus as EventBusInterface;
use EventSourcing\Stream;
use Symfony\Component\Messenger\MessageBusInterface;

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
            $this->eventBus->dispatch($message);
        }
    }
}