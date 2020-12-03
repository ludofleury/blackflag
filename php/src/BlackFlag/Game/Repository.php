<?php
declare(strict_types=1);

namespace BlackFlag\Game;

use Ramsey\Uuid\UuidInterface;

interface Repository
{
    public function save(Session $session): void;

    public function load(SessionId $sessionId): Session;
}