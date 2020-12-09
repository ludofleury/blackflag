<?php
declare(strict_types=1);

namespace EventSourcing;

abstract class MessageHandler
{
    final public function __invoke(Message $message): void
    {
        $method = $this->getApplyMethod($message->getEvent());
        if (!method_exists($this, $method)) {
            return;
        }

        $this->$method($message->getEvent(), $message);
    }

    private function getApplyMethod(Event $event): string
    {
        $classParts = explode('\\', $event::class);

        return 'apply'.end($classParts);
    }
}