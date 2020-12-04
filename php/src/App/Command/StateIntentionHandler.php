<?php
declare(strict_types=1);

namespace App\Command;

use App\CommandHandler;
use App\Repository\CharacterRepository;
use App\Repository\SessionRepository;
use BlackFlag\PlayableCharacter\Exception\CharacterNotFoundException;

class StateIntentionHandler implements CommandHandler
{
    public function __construct(
        private SessionRepository $sessionRepository,
        private CharacterRepository $characterRepository,
    )
    {
    }

    public function __invoke(StateIntention $command): void
    {
        $session = $this->sessionRepository->load($command->getSessionId());

        try {
            $character = $this->characterRepository->load($command->getCharacterId());
        } catch (CharacterNotFoundException $exception) {
            throw $exception;
        }

        $session->registerIntention(
            $command->getCharacterId(),
            $command->getAttributeName(),
            $command->getSkillDomain(),
        );

        $this->sessionRepository->save($session);
    }
}
/**
joueur -> request opportunity -> buffer -> mj validate -> roll dice -> success: perform action
                                        -> mj refuse                -> fail: register attempt
 */