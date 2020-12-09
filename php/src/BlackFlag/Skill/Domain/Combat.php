<?php
declare(strict_types=1);

namespace BlackFlag\Skill\Domain;

interface Combat
{
    public const MELEE = 'melee weapon';
    public const MELEE_BATON = 'baton';
    public const MELEE_SCIMITAR = 'scimitar';
    public const MELEE_HOOK = 'hook';
    public const MELEE_DAGGER = 'dagger';
    public const MELEE_AXE = 'axe';
    public const MELEE_SPEAR = 'spear/spontoon';
    public const MELEE_RAPIER = 'rapier/short sword';
    public const MELEE_SABRE = 'sabre';
    public const MELEE_WHIP = 'whip';
    public const FLINTLOCK = 'flintlock weapon';
    public const FLINTLOCK_MUSKET = 'musket';
    public const FLINTLOCK_PISTOL = 'pistol';
    public const FLINTLOCK_GRENADE = 'grenade';
    public const RANGED = 'ranged weapon';
    public const RANGED_CROSSBOW = 'crossbow';
    public const RANGED_BOW = 'bow';
    public const RANGED_DAGGER_THROW = 'dagger throw';
    public const RANGED_JAVELIN = 'javelin/harpoon';
    public const RANGED_SARBACANE = 'sarbacane';
    public const HAND_FIGHTING = 'hand fighting';
    public const FENCING = 'fencing';
    public const DODGING = 'dodging';
}