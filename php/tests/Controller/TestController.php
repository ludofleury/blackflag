<?php
declare(strict_types=1);

namespace Tests\Controller;

use App\Command\StateIntention;
use App\Repository\SessionRepository;
use BlackFlag\Game\MasterId;
use BlackFlag\Game\Session;
use BlackFlag\PlayableCharacter\CharacterId;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/debug', name: 'test_debug')]
class TestController
{
    public function __invoke(LoggerInterface $logger, SessionRepository $sessionRepository, MessageBusInterface $commandBus): Response
    {
        $this->createSession($sessionRepository, $commandBus);
        $logger->info('test debug controller called');
        return new Response('<html lang="en"><body>test debug</body></html>');
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