<?php
declare(strict_types=1);

namespace BlackFlag\PlayableCharacter;

use EventSourcing\Identifier;
use Ramsey\Uuid\Uuid;

final class CharacterId extends Identifier
{
    public function equals(CharacterId $other): bool
    {
        return $this->value->equals($other->value);
    }

    public static function fromString(string $uuid): CharacterId
    {
        return new self(Uuid::fromString($uuid));
    }
}