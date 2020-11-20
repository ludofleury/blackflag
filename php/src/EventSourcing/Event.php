<?php

namespace EventSourcing;

interface Event
{
    static public function fromArray(array $data): self;

    public function toArray(): array;
}