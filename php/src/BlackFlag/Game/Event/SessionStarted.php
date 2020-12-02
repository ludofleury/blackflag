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

    /** @var array<int, CharacterId|Identifier>  */
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
            MasterId::fromString($data['master']),
            ...self::normalizeIdentifiers(CharacterId::class, $data['characters'])
        );
    }

    /**
     * @return array{master: string, characters: string[]}
     */
    public function toArray(): array
    {
        return [
            'master' => $this->masterId->toString(),
            'characters' => $this->denormalizeIdentifiers(...$this->charactersIds)
        ];
    }

    /** @var array<int, CharacterId|Identifier>  */
    public function getMasterId(): MasterId
    {
        return $this->masterId;
    }

    public function getCharactersIds(): array
    {
        return $this->charactersIds;
    }
}