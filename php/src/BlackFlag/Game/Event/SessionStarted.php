<?php
declare(strict_types=1);

namespace BlackFlag\Game\Event;

use BlackFlag\Game\MasterId;
use BlackFlag\PlayableCharacter\CharacterId;
use EventSourcing\Event;
use EventSourcing\Identifier;
use EventSourcing\Normalizer\IdentifiersNormalizer;

class SessionStarted implements Event
{
    use IdentifiersNormalizer;

    private MasterId $masterId;

    /** @var array<int, CharacterId>  */
    private array $charactersIds;

    public function __construct(
        MasterId $masterId,
        CharacterId ...$characterIds,
    )
    {
        $this->masterId = $masterId;
        $this->charactersIds = $characterIds;
    }

    static public function fromArray(array $data): SessionStarted
    {
        return new self(
            MasterId::fromString($data['masterId']),
            ...self::normalizeIdentifiers(CharacterId::class, $data['characterIds'])
        );
    }

    /**
     * @return array{masterId: string, characterIds: string[]}
     */
    public function toArray(): array
    {
        return [
            'masterId' => $this->masterId->toString(),
            'characterIds' => $this->denormalizeIdentifiers(...$this->charactersIds)
        ];
    }

    public function getMasterId(): MasterId
    {
        return $this->masterId;
    }

    /** @return array<int, CharacterId>  */
    public function getCharactersIds(): array
    {
        return $this->charactersIds;
    }
}