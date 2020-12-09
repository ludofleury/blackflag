<?php
declare(strict_types=1);

namespace Tests\Controller;

use App\Command\StateIntention;
use App\Repository\CharacterRepository;
use App\Repository\SessionRepository;
use BlackFlag\Attribute\Characteristic;
use BlackFlag\Game\MasterId;
use BlackFlag\Game\Session;
use BlackFlag\PlayableCharacter\Character;
use BlackFlag\PlayableCharacter\CharacterId;
use BlackFlag\Skill\Domain\{Combat,Technical};
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/debug', name: 'test_debug')]
class TestController
{
    public function __invoke(
        LoggerInterface $logger,
        MessageBusInterface $commandBus,
        SessionRepository $sessionRepository,
        CharacterRepository $characterRepository,
    ): Response
    {
        // $this->createCharacter($characterRepository);
        // $this->improveAttribute($characterRepository);
        // $this->createSession($sessionRepository, $commandBus);
        $logger->info('test debug controller called');
        return new Response('<html lang="en"><body>test debug</body></html>');
    }

    private function createCharacter(CharacterRepository $characterRepository): void
    {
        $john = Character::create(
            'John',
            'Doe',
            'Black beard',
            35,
            true,
            [
                Characteristic::ADAPTABILITY => 5,
                Characteristic::CHARISMA     => 5,
                Characteristic::CONSTITUTION => 5,
                Characteristic::DEXTERITY    => 5,
                Characteristic::EXPRESSION   => 5,
                Characteristic::KNOWLEDGE    => 5,
                Characteristic::PERCEPTION   => 5,
                Characteristic::POWER        => 5,
                Characteristic::STRENGTH     => 6,
            ],
            [
                ['name' => Combat::DODGING, 'level' => 1],
                ['name' => Combat::MELEE, 'special' => Combat::MELEE_AXE, 'level' => 2],
                ['name' => Technical::ART, 'special' => 'singing', 'level' => 3, 'pro' => true],
            ]
        );

        $characterRepository->save($john);
    }

    private function createSession(SessionRepository $sessionRepository, MessageBusInterface $commandBus): void
    {
        $john = new CharacterId(Uuid::uuid4());

        $session = Session::start(
            new MasterId(Uuid::uuid4()),
            ...[
                $john,
                new CharacterId(Uuid::uuid4()),
            ]
        );
        $sessionRepository->save($session);

        $stateIntention = new StateIntention(
            $session->getId()->toString(),
            $john->toString(),
            'adaptability',
            'ballistics'
        );

        $commandBus->dispatch($stateIntention);
    }


}