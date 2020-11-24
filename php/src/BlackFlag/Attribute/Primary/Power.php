<?php
declare(strict_types=1);

namespace BlackFlag\Attribute\Primary;

use Rpg\Reference as RPG;

#[RPG\Book(ISBN: '978-2-36328-252-1', page: 13)]
#[RPG\Term(lang: 'fr', text: 'pouvoir')]
final class Power extends AbstractPrimary
{
    public const name = 'power';

    public function getName(): string
    {
        return self::name;
    }
}