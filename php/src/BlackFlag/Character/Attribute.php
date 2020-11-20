<?php

namespace BlackFlag\Character;

use BlackFlag\Character\Exception\UnknownAttributeException;
use Rpg\AbstractStatistic;
use Rpg\Statistic;

/**
 * Primary statistic for Black Flag characters
 * Also called (in-born) characteristic
 *
 * @see https://en.wikipedia.org/wiki/Attribute_(role-playing_games)
 */
final class Attribute extends AbstractStatistic implements Statistic
{
    static private array $RULES = [
        'adaptability' => ['min' => 3, 'max' => 8], // adaptabilité
        'charisma'     => ['min' => 3, 'max' => 8], // charisma
        'constitution' => ['min' => 5, 'max' => 8], // résistance
        'dexterity'    => ['min' => 3, 'max' => 8], // adresse
        'expression'   => ['min' => 3, 'max' => 8], // expression
        'knowledge'    => ['min' => 3, 'max' => 8], // érudition
        'perception'   => ['min' => 3, 'max' => 8], // perception
        'power'        => ['min' => 3, 'max' => 8], // pouvoir
        'strength'     => ['min' => 3, 'max' => 8], // force
    ];

    public function __construct(string $name, int $value)
    {
        if (!isset(self::$RULES[$name])) {
            throw new UnknownAttributeException($name);
        }

        parent::__construct($name, $value, self::$RULES[$name]['min'], self::$RULES[$name]['max']);
    }

    static public function getSupportedAttributes(): array
    {
        return self::$RULES;
    }
}