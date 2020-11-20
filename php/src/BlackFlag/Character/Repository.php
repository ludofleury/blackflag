<?php

namespace BlackFlag\Character;

use Ramsey\Uuid\UuidInterface;

interface Repository
{
    public function save(Character $character);

    public function load(UuidInterface $uuid): Character;
}