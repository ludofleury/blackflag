<?php
declare(strict_types=1);

namespace BlackFlag\Attribute;

use DomainException;
use Rpg\Reference as RPG;

#[RPG\Book(ISBN: '978-2-36328-252-1', page: 13)]
#[RPG\Term(lang: 'fr', text: 'caractéristique')]
class Characteristic
{
    public const ADAPTABILITY = 'adaptability';
    public const CHARISMA = 'charisma';
    public const CONSTITUTION = 'constitution';
    public const DEXTERITY = 'dexterity';
    public const EXPRESSION = 'expression';
    public const KNOWLEDGE = 'knowledge';
    public const PERCEPTION = 'perception';
    public const POWER = 'power';
    public const STRENGTH = 'strength';

    private static array $list = [
        self::ADAPTABILITY => true,
        self::CHARISMA => true,
        self::CONSTITUTION => true,
        self::DEXTERITY => true,
        self::EXPRESSION => true,
        self::KNOWLEDGE => true,
        self::PERCEPTION => true,
        self::POWER => true,
        self::STRENGTH => true,
    ];

    private string $name;

    public function __construct(string $name)
    {
        if (!isset(self::$list[$name])) {
            throw new DomainException(sprintf('Unknown characteristic "%s"', $name));
        }
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public static function list(): array
    {
        return array_keys(self::$list);
    }
}