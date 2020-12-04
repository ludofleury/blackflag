<?php
declare(strict_types=1);

namespace BlackFlag\PlayableCharacter\Event;

use EventSourcing\Event;

class CharacterImprovedAttribute implements Event
{
    private string $name;

    private int $progress;

    public function __construct(string $name, int $progress)
    {
        $this->name = $name;
        $this->progress = $progress;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getProgress(): int
    {
        return $this->progress;
    }

    static public function fromArray(array $data): Event
    {
        return new self($data['name'], $data['progress']);
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'progress' => $this->progress
        ];
    }
}