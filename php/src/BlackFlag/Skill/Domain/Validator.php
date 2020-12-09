<?php
declare(strict_types=1);

namespace BlackFlag\Skill\Domain;

trait Validator
{
    /** @var array<string, bool>  */
    private static array $officials = [
        Knowledge::BALLISTICS        => true,
        Knowledge::CARTOGRAPHY       => true,
        Knowledge::TRADE             => true,
        Knowledge::EXPERTISE         => true,
        Knowledge::HERBALISM         => true,
        Knowledge::NAVAL_ENGINEERING => true,
        Knowledge::STEWARDSHIP       => true,
        Knowledge::READ_WRITE        => true,
        Knowledge::MEDICINE          => true,
        Knowledge::RELIGION          => true,
        Knowledge::SCIENCES          => true,
        Knowledge::TACTIC            => true,
        Technical::ART                     => true,
        Technical::ARTILLERY_GUN_LAYING    => true,
        Technical::ARTILLERY_GUN_RELOADING => true,
        Technical::CRAFTING                => true,
        Technical::HUNTING                 => true,
        Technical::SURGERY                 => true,
        Technical::ANIMAL_TRAINING         => true,
        Technical::FIRST_AID               => true,
        Maritime::SAILING              => true,
        Maritime::SHIP_KNOWLEDGE       => true,
        Maritime::SIGNALING            => true,
        Maritime::HYDROGRAPHY          => true,
        Maritime::NAVIGATION           => true,
        Maritime::FISHING              => true,
        Maritime::SEAMANSHIP_PRACTICES => true,
        Maritime::STEERING             => true,
        Physical::ACROBATICS   => true,
        Physical::ATHLETICS    => true,
        Physical::STEALTH      => true,
        Physical::HORSE_RIDING => true,
        Physical::BURGLARY     => true,
        Physical::SWIMMING     => true,
        Physical::SURVIVAL     => true,
        Physical::VIGILANCE    => true,
        Social::ACTING             => true,
        Social::KNOWLEDGE_SETTLERS => true,
        Social::KNOWLEDGE_SEAMEN   => true,
        Social::EMPATHY            => true,
        Social::TEACHING           => true,
        Social::ETIQUETTE          => true,
        Social::INTIMIDATION       => true,
        Social::GAMING             => true,
        Social::FOREIGN_LANGUAGE   => true,
        Social::LEADERSHIP         => true,
        Social::PERSUASION         => true,
        Social::POLITIC            => true,
        Social::SEDUCTION          => true,
        Combat::MELEE         => true,
        Combat::FLINTLOCK     => true,
        Combat::RANGED        => true,
        Combat::HAND_FIGHTING => true,
        Combat::FENCING       => true,
        Combat::DODGING       => true,
    ];

    public function supports(string $name): bool
    {
        return isset(self::$officials[$name]);
    }
}