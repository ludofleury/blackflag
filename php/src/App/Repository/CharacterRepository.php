<?php

namespace App\Repository;

use BlackFlag\Character\Character;
use BlackFlag\Character\Exception\CharacterNotFoundException;
use BlackFlag\Character\Repository;
use EventSourcing\EventBus;
use EventSourcing\EventStore;
use EventSourcing\Exception\AggregateRootNotFoundException;
use EventSourcing\Repository as EsRepository;
use Ramsey\Uuid\UuidInterface;

class CharacterRepository extends EsRepository implements Repository
{
    public function __construct(EventStore $eventStore, EventBus $eventBus)
    {
        parent::__construct(Character::class, $eventStore, $eventBus);
    }

    public function load(UuidInterface $characterId): Character
    {
        try {
            /** @var Character $character */
            $character = parent::load($characterId);
        } catch (AggregateRootNotFoundException $exception) {
            throw new CharacterNotFoundException(sprintf('Character "%s" not found', $characterId), null, $exception);
        }

        return $character;
    }
}