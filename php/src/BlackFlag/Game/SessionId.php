<?php
declare(strict_types=1);

namespace BlackFlag\Game;

use EventSourcing\Identifier;
use Ramsey\Uuid\Uuid;

class SessionId extends Identifier
{
    public static function fromString(string $uuid): SessionId
    {
        return new self(Uuid::fromString($uuid));
    }
}