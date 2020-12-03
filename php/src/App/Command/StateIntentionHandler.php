<?php
declare(strict_types=1);

namespace App\Command;

use App\CommandHandler;
use App\Repository\SessionRepository;

class StateIntentionHandler implements CommandHandler
{
    public function __construct(
        private SessionRepository $sessionRepository
    )
    {
    }

    public function __invoke(StateIntention $command): void
    {
        $session = $this->sessionRepository->load($command->getSessionId());

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