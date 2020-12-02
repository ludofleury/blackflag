<?php
declare(strict_types=1);

namespace BlackFlag\Game\Event;

use BlackFlag\PlayableCharacter\CharacterId;
use EventSourcing\Event;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class IntentionStated implements Event
{
    private CharacterId $characterId;

    private UuidInterface $intentionId;

    private string $attribute;

    private string $skill;

    private array $modifiers;

    public function __construct(UuidInterface $intentionId, CharacterId $characterId, string $attribute, string $skill, array $modifiers)
    {
        $this->intentionId = $intentionId;
        $this->characterId = $characterId;
        $this->attribute = $attribute;
        $this->skill = $skill;
        $this->modifiers = $modifiers;
    }

    public function getIntentionId(): UuidInterface
    {
        return $this->intentionId;
    }

    public function getCharacterId(): CharacterId
    {
        return $this->characterId;
    }

    public function getAttribute(): string
    {
        return $this->attribute;
    }

    public function getSkill(): string
    {
        return $this->skill;
    }

    public function getModifiers(): array
    {
        return $this->modifiers;
    }

    static public function fromArray(array $data): self
    {
        return new self(
            Uuid::fromString($data['intentionId']),
            CharacterId::fromString($data['characterId']),
            $data['attribute'],
            $data['skill'],
            $data['modifiers'],
        );
    }

    public function toArray(): array
    {
        return [
            'intentionId' => $this->intentionId->toString(),
            'character' => $this->characterId->toString(),
            'attribute' => $this->attribute,
            'skill' => $this->skill,
            'modifier' => $this->modifiers,
        ];
    }

}