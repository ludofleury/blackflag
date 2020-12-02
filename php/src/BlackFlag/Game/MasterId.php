<?php
declare(strict_types=1);

namespace BlackFlag\Game;

use EventSourcing\Identifier;
use Ramsey\Uuid\Uuid;

final class MasterId extends Identifier
{
    public function equals(MasterId $other): bool
    {
        return $this->value->equals($other->value);
    }

    public static function fromString(string $uuid): MasterId
    {
        return new self(Uuid::fromString($uuid));
    }
}