<?php
declare(strict_types=1);

namespace BlackFlag\Game;

use BlackFlag\Game\Event\SessionStarted;
use BlackFlag\PlayableCharacter\CharacterId;
use EventSourcing\ChildEntity;

/**
 * The full cast of characters involved in a session
 */
class Roster extends ChildEntity
{
    /** @var array<string, CharacterId>  */
    private array $registered;

    public function __construct(Session $session)
    {
        parent::__construct($session);
    }

    public function hasRegistered(CharacterId $characterId): bool
    {
        return isset($this->registered[$characterId->toString()]);
    }

    public function applySessionStarted(SessionStarted $sessionStarted): void
    {
        $characterIds = $sessionStarted->getCharactersIds();
        foreach ($characterIds as $id) {
            $this->registered[$id->toString()] = $id;
        }
    }
}