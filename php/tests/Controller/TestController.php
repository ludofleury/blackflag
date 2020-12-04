<?php
declare(strict_types=1);

namespace Tests\Controller;

use App\Command\StateIntention;
use App\Repository\CharacterRepository;
use App\Repository\SessionRepository;
use BlackFlag\Attribute\Primary\Adaptability;
use BlackFlag\Attribute\Primary\Charisma;
use BlackFlag\Attribute\Primary\Constitution;
use BlackFlag\Attribute\Primary\Dexterity;
use BlackFlag\Attribute\Primary\Expression;
use BlackFlag\Attribute\Primary\Knowledge;
use BlackFlag\Attribute\Primary\Perception;
use BlackFlag\Attribute\Primary\Power;
use BlackFlag\Attribute\Primary\Strength;
use BlackFlag\Game\MasterId;
use BlackFlag\Game\Session;
use BlackFlag\PlayableCharacter\Character;
use BlackFlag\PlayableCharacter\CharacterId;
use BlackFlag\Skill\Combat;
use BlackFlag\Skill\Technical;
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
        $this->createCharacter($characterRepository);
        // $this->createSession($sessionRepository, $commandBus);
        $logger->info('test debug controller called');
        return new Response('<html lang="en"><body>test debug</body></html>');
    }

    private function createCharacter(CharacterRepository $characterRepository)
    {
        $john = Character::create(
            'John',
            'Doe',
            'Black beard',
            35,
            true,
            [
                Adaptability::name => 5,
                Charisma::name     => 5,
                Constitution::name => 5,
                Dexterity::name    => 5,
                Expression::name   => 5,
                Knowledge::name    => 5,
                Perception::name   => 5,
                Power::name        => 5,
                Strength::name     => 6,
            ],
            [
                ['name' => Combat::DODGING, 'level' => 1],
                ['name' => Combat::MELEE, 'special' => Combat::MELEE_AXE, 'level' => 2],
                ['name' => Technical::ART, 'special' => 'singing', 'level' => 3, 'pro' => true],
            ]
        );

        $characterRepository->save($john);
    }

    private function createSession(SessionRepository $sessionRepository, MessageBusInterface $commandBus)
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