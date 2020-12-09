<?php
declare(strict_types=1);

namespace App\Projector;

use App\Projection\CharacterSheet;
use App\ProjectionEntityManager;
use App\Projector;
use BlackFlag\PlayableCharacter\Event\CharacterCreated;
use BlackFlag\PlayableCharacter\Event\CharacterImprovedAttribute;
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
            $characterCreated->getAttributes(),
            $characterCreated->getSkills()
        );

        $this->persister->persist($sheet);
        $this->persister->flush();
    }

    protected function applyCharacterImprovedAttribute(CharacterImprovedAttribute $event, Message $message): void
    {
        $repository = $this->persister->getRepository(CharacterSheet::class);
        $sheet = $repository->find($message->getAggregateRootId());

        if ($sheet === null) {
            throw new \RuntimeException('Unable to retrieve character sheet');
        }

        $attribute = $event->getName();

        $sheet->attributes->$attribute += $event->getProgress();

        $this->persister->persist($sheet);
        $this->persister->flush();
    }
}