<?php
declare(strict_types=1);

namespace BlackFlag\Game\Event;

use BlackFlag\PlayableCharacter\CharacterId;
use BlackFlag\Skill\Domain;
use EventSourcing\Event;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class IntentionStated implements Event
{
    private CharacterId $characterId;

    private UuidInterface $intentionId;

    private string $attribute;

    private Domain $skill;

    public function __construct(UuidInterface $intentionId, CharacterId $characterId, string $attribute, Domain $skill)
    {
        $this->intentionId = $intentionId;
        $this->characterId = $characterId;
        $this->attribute = $attribute;
        $this->skill = $skill;
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

    public function getSkillDomain(): Domain
    {
        return $this->skill;
    }

    static public function fromArray(array $data): self
    {
        return new self(
            Uuid::fromString($data['intentionId']),
            CharacterId::fromString($data['characterId']),
            $data['attribute'],
            new Domain($data['skill']['name'], $data['skill']['specialization'] ?? null),
        );
    }

    /**
     * @return array{
     *  intentionId: string,
     *  characterId: string,
     *  attribute:string,
     *  skill: array{ name: string, specialization?: string }
     * }
     */
    public function toArray(): array
    {
        $skill = ['name' => $this->skill->getName()];
        if ($this->skill->isSpecialized()) {
            $skill['specialization'] = $this->skill->getSpecialization();
        }

        return [
            'intentionId' => $this->intentionId->toString(),
            'characterId' => $this->characterId->toString(),
            'attribute' => $this->attribute,
            'skill' => $skill,
        ];
    }

}