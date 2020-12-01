<?php
declare(strict_types=1);

namespace BlackFlag\Resolution;

use BlackFlag\Participant\PrimaryAttributeLevel;
use Rpg\Reference as RPG;

/**
 * The ease of an action (also known as difficulty)
 *
 * 🎲 equal or lower than this threshold is a success
 * Usually defined by primary attribute, professional value, command value.
 */
#[RPG\Book(ISBN: '978-2-36328-252-1', page: 95)]
#[RPG\Term(lang: 'fr', text: 'facilité')]
final class Facility
{
    private int $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function getThreshold(): int
    {
        return $this->value;
    }
}