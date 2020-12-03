<?php
declare(strict_types=1);

namespace App\Repository;

use BlackFlag\Game\Session;
use BlackFlag\Game\Repository;
use BlackFlag\Game\SessionId;
use EventSourcing\EventBus;
use EventSourcing\EventStore;
use EventSourcing\Exception\AggregateRootNotFoundException;
use EventSourcing\Repository as EsRepository;
use Ramsey\Uuid\UuidInterface;

class SessionRepository extends EsRepository implements Repository
{
    public function __construct(EventStore $eventStore, EventBus $eventBus)
    {
        parent::__construct(Session::class, $eventStore, $eventBus);
    }

    public function load(SessionId|UuidInterface $sessionId): Session
    {
        try {
            /** @var Session $session */
            $session = parent::load($sessionId instanceof SessionId ? $sessionId->getValue() : $sessionId);
        } catch (AggregateRootNotFoundException $exception) {
            throw new \RuntimeException(
                sprintf('Session "%s" not found', $sessionId->toString()),
                0,
                $exception
            );
        }

        return $session;
    }
}