<?php

namespace EventSourcing;

abstract class Entity
{
    /**
     * @var ChildEntity[]
     */
    protected array $childEntities = [];

    abstract protected function apply(Event $event): void;

    protected function handleRecursively(Event $event): void
    {
        $this->handle($event);

        foreach ($this->getChildEntities() as $entity) {
            $entity->handleRecursively($event);
        }
    }

    /**
     * Apply a domain event to the AR without recording it
     */
    protected function handle(Event $event): void
    {
        $method = $this->getApplyMethod($event);

        if (!method_exists($this, $method)) {
            return;
        }

        $this->$method($event);
    }

    protected function getApplyMethod(Event $event): string
    {
        $classParts = explode('\\', get_class($event));

        return 'apply'.end($classParts);
    }

    protected function getChildEntities(): array
    {
        return $this->childEntities;
    }
}