<?php
declare(strict_types=1);

namespace BlackFlag\Character;

use Ramsey\Uuid\UuidInterface;

interface Repository
{
    public function save(Character $character): void;

    public function load(UuidInterface $uuid): Character;
}