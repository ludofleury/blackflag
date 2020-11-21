<?php
declare(strict_types=1);

namespace EventSourcing;

interface Event
{
    /**
     * @param array<string, mixed> $data
     */
    static public function fromArray(array $data): self;

    /**
     * @return array<string, mixed> $data
     */
    public function toArray(): array;
}   