<?php
declare(strict_types=1);

namespace BlackFlag\PlayableCharacter;

use Ramsey\Uuid\UuidInterface;

interface Repository
{
    public function save(Character $character): void;

    public function load(CharacterId $characterId): Character;
}