<?php
declare(strict_types=1);

namespace App\Command;

use BlackFlag\Game\SessionId;
use BlackFlag\PlayableCharacter\CharacterId;
use BlackFlag\Skill\Domain;

class StateIntention
{
    public function __construct(
        private string $sessionId,
        private string $characterId,
        private string $attributeName,
        private string $skillName,
        private ?string $skillSpecialization = null,
    )
    {
    }

    public function getSessionId(): SessionId
    {
        return SessionId::fromString($this->sessionId);
    }

    public function getCharacterId(): CharacterId
    {
        return CharacterId::fromString($this->characterId);
    }

    public function getAttributeName(): string
    {
        return $this->attributeName;
    }

    public function getSkillDomain(): Domain
    {
        return new Domain($this->skillName, $this->skillSpecialization);
    }
}