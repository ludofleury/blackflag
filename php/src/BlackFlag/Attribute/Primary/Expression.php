<?php
declare(strict_types=1);

namespace BlackFlag\Attribute\Primary;

use Rpg\Reference as RPG;

#[RPG\Book(ISBN: '978-2-36328-252-1', page: 13)]
#[RPG\Term(lang: 'fr', text: 'expression')]
final class Expression extends AbstractPrimary
{
    public const name = 'expression';

    public function getName(): string
    {
        return self::name;
    }
}