<?php
declare(strict_types=1);

namespace App\Projector;

use BlackFlag\Game\Event\SessionStarted;

class GameSessionAttendees
{
    private string $repository;

    public function applySessionStarted(SessionStarted $event)
    {

    }
}