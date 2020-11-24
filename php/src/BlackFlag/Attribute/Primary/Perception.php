<?php
declare(strict_types=1);

namespace BlackFlag\Attribute\Primary;

use Rpg\Reference as RPG;

#[RPG\Book(ISBN: '978-2-36328-252-1', page: 13)]
#[RPG\Term(lang: 'fr', text: 'perception')]
final class Perception extends AbstractPrimary
{
    public const name = 'perception';

    public function getName(): string
    {
        return self::name;
    }
}