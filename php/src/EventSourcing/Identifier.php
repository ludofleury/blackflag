<?php
declare(strict_types=1);

namespace EventSourcing;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class Identifier
{
    protected UuidInterface $value;

    final public function __construct(UuidInterface $id)
    {
        $this->value = $id;
    }

    final public function getValue(): UuidInterface
    {
        return $this->value;
    }

    final public function __toString(): string
    {
        return $this->value->toString();
    }

    abstract public static function fromString(string $uuid): Identifier;

    final public function toString(): string
    {
        return $this->value->toString();
    }
}