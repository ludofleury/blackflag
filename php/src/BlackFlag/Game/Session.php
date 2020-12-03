<?php
declare(strict_types=1);

namespace BlackFlag\Game;

use BlackFlag\Game\Event\IntentionStated;
use BlackFlag\Game\Event\SessionStarted;
use BlackFlag\Game\Exception\CharacterNotRegistered;
use BlackFlag\PlayableCharacter\CharacterId;
use BlackFlag\Resolution\Efficiency;
use BlackFlag\Resolution\Facility;
use BlackFlag\Skill\Domain;
use EventSourcing\AggregateRoot;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Session extends AggregateRoot
{
    private Roster $roster;

    /** @var array<string, array<string, bool>> */
    private array $intentions;

    public static function start(
        MasterId $masterId,
        CharacterId ...$characterIds
    ): Session {
       $session = new Session(Uuid::uuid4());

        $event = new SessionStarted(
            $masterId,
            ...$characterIds,
        );
        $session->apply($event);

        return $session;
    }

    public function getId(): SessionId
    {
        return new SessionId($this->getAggregateRootId());
    }

    public function registerIntention(CharacterId $characterId, string $attribute, Domain $skill): void
    {
        if (!$this->roster->hasRegistered($characterId)) {
            throw new CharacterNotRegistered(sprintf('Unable to state intention: character "%s" is not registered to this session', $characterId->toString()));
        }

        $this->apply(
            new IntentionStated(
                Uuid::uuid4(),
                $characterId,
                $attribute,
                $skill,
            )
        );
    }

    public function discardIntention(UuidInterface $intentionId): void
    {
    }

    public function resolveAction(CharacterId $characterId, Efficiency $efficiency, Facility $facility, ?UuidInterface $relatedIntention = null): void
    {

    }

    protected function applySessionStarted(SessionStarted $event): void
    {
        $this->roster = new Roster($this);
    }

    protected function applyIntentionStated(IntentionStated $event): void
    {
        $characterId = $event->getCharacterId();
        $intentionId = $event->getIntentionId();
        $this->intentions[$characterId->toString()][$intentionId->toString()] = true;
    }
}