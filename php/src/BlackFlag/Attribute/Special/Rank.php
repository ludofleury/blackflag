<?php
declare(strict_types=1);

namespace BlackFlag\Attribute\Special;

use DomainException;

#[RPG\Book(ISBN: '978-2-36328-252-1', page: 13)]
#[RPG\Term(lang: 'fr', text: 'caractéristique')]
class Rank
{
    const CAPTAIN = 'captain';
    const FIRST_MATE = 'first mate';
    const GUNNER = 'gunner';
    const QUARTER_MASTER = 'quarter master';
    const BOATSWAIN = 'boatswain';
    const GUNNER_MATE = 'gunner mate';

    private const MAP = [
      self::CAPTAIN => true,
      self::FIRST_MATE => true,
      self::GUNNER => true,
      self::QUARTER_MASTER => true,
      self::BOATSWAIN => true,
      self::GUNNER_MATE => true,
    ];

    private string $name;

    public function __construct(string $name)
    {
        if (!isset(self::MAP[$name])) {
            throw new DomainException(sprintf('Unknown rank "%s"', $name));
        }

        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}