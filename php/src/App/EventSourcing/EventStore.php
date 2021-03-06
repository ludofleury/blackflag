<?php
declare(strict_types=1);

namespace App\EventSourcing;

use EventSourcing\EventStore as EventStoreInterface;
use EventSourcing\Message;
use EventSourcing\Exception\EventStoreException;
use EventSourcing\Stream;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Ramsey\Uuid\UuidInterface;
use Throwable;

final class EventStore extends ServiceEntityRepository implements EventStoreInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function append(Stream $stream): void
    {
        $manager = $this->getEntityManager();

        try {
            foreach ($stream as $message) {
                $manager->persist($message);
            }

            $manager->transactional(
                function (EntityManagerInterface $manager) {
                    $manager->flush();
                }
            );
        } catch (Throwable $exception) {
            throw new EventStoreException('Unable to persist event stream', 0, $exception);
        }
    }

    public function load(string $aggregateRootType, UuidInterface $aggregateRootId): Stream
    {
        try {
            $messages = $this->createQueryBuilder('e')
                ->where('e.aggregateRootId = :id AND e.aggregateRootType = :type') // column orders matters to optimize multi-columns index
                ->orderBy('e.sequence', 'ASC')
                ->setParameter('id', $aggregateRootId, 'uuid')
                ->setParameter('type', $aggregateRootType)
                ->getQuery()
                ->execute();
        } catch (Throwable $exception) {
            throw new EventStoreException(sprintf('Unable to load event stream for %s "%s"', $aggregateRootType, $aggregateRootId->toString()), 0, $exception);
        }

        return new Stream(...$messages);
    }
}