<?php
declare(strict_types=1);

namespace App\Projector;

use App\Projection\CharacterSheet;
use App\ProjectionEntityManager;
use App\Projector;
use BlackFlag\PlayableCharacter\Event\CharacterCreated;
use EventSourcing\Message;
use EventSourcing\MessageHandler;

class CharacterSheetProjector extends MessageHandler implements Projector
{
    private ProjectionEntityManager $persister;

    public function __construct(ProjectionEntityManager $projection)
    {
        $this->persister = $projection;
    }

    protected function applyCharacterCreated(CharacterCreated $characterCreated, Message $message): void
    {
        $sheet = new CharacterSheet(
            $message->getAggregateRootId(),
            $characterCreated->getFirstname(),
            $characterCreated->getLastname(),
            $characterCreated->getNickname(),
            $characterCreated->getAge(),
            0,
            $characterCreated->getCharacteristics(),
            $characterCreated->getSkills()
        );

        $this->persister->persist($sheet);
        $this->persister->flush();
    }
}