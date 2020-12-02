<?php
declare(strict_types=1);

namespace App\Repository;

use BlackFlag\PlayableCharacter\Character;
use BlackFlag\PlayableCharacter\CharacterId;
use BlackFlag\PlayableCharacter\Exception\CharacterNotFoundException;
use BlackFlag\PlayableCharacter\Repository;
use EventSourcing\EventBus;
use EventSourcing\EventStore;
use EventSourcing\Exception\AggregateRootNotFoundException;
use EventSourcing\Identifier;
use EventSourcing\Repository as EsRepository;
use Ramsey\Uuid\UuidInterface;

class CharacterRepository extends EsRepository implements Repository
{
    public function __construct(EventStore $eventStore, EventBus $eventBus)
    {
        parent::__construct(Character::class, $eventStore, $eventBus);
    }

    public function load(CharacterId|UuidInterface $characterId): Character
    {
        try {
            /** @var Character $character */
            $character = parent::load($characterId instanceof CharacterId ? $characterId->getValue() : $characterId);
        } catch (AggregateRootNotFoundException $exception) {
            throw new CharacterNotFoundException(
                sprintf('Character "%s" not found', $characterId->toString()),
                0,
                $exception
            );
        }

        return $character;
    }
}