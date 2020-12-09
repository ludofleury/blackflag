<?php
declare(strict_types=1);

use BlackFlag\SKill\Domain\Combat;
use BlackFlag\SKill\Domain\Knowledge;
use BlackFlag\SKill\Domain\Maritime;
use BlackFlag\SKill\Domain\Physical;
use BlackFlag\SKill\Domain\Social;
use BlackFlag\SKill\Domain\Technical;
use BlackFlag\SKill\Registry;

return [
        Knowledge::BALLISTICS  => Registry::SIMPLE | Registry::PRO,
        Knowledge::CARTOGRAPHY => Registry::SIMPLE | Registry::PRO,
        Knowledge::TRADE       => Registry::SIMPLE,

        Knowledge::EXPERTISE           => Registry::MACRO,
        Knowledge::EXPERTISE_LAW       => Registry::SPECIAL | Registry::PRO,
        Knowledge::EXPERTISE_GEOGRAPHY => Registry::SPECIAL,
        Knowledge::EXPERTISE_HISTORY   => Registry::SPECIAL,

        Knowledge::HERBALISM         => Registry::SIMPLE | Registry::PRO,
        Knowledge::NAVAL_ENGINEERING => Registry::SIMPLE | Registry::PRO,
        Knowledge::STEWARDSHIP       => Registry::SIMPLE | Registry::PRO,
        Knowledge::READ_WRITE        => Registry::SIMPLE | Registry::PRO,
        Knowledge::MEDICINE          => Registry::SIMPLE | Registry::PRO,
        Knowledge::RELIGION          => Registry::MACRO,
        Knowledge::SCIENCES          => Registry::SIMPLE | Registry::PRO,
        Knowledge::TACTIC            => Registry::SIMPLE,

        Technical::ART                     => Registry::MACRO, # any specialization inside should be pro
        Technical::ARTILLERY_GUN_LAYING    => Registry::SIMPLE,
        Technical::ARTILLERY_GUN_RELOADING => Registry::SIMPLE,

        Technical::CRAFTING           => Registry::MACRO,
        Technical::CRAFTING_CAULK     => Registry::SPECIAL | Registry::PRO,
        Technical::CRAFTING_CARPENTRY => Registry::SPECIAL | Registry::PRO,
        Technical::CRAFTING_SAILMAKER => Registry::SPECIAL | Registry::PRO,
        Technical::CRAFTING_COOKING   => Registry::SPECIAL,

        Technical::HUNTING         => Registry::SIMPLE,
        Technical::SURGERY         => Registry::SIMPLE | Registry::PRO,
        Technical::ANIMAL_TRAINING => Registry::SIMPLE,
        Technical::FIRST_AID       => Registry::SIMPLE,

        Maritime::SAILING              => Registry::SIMPLE | Registry::PRO,
        Maritime::SHIP_KNOWLEDGE       => Registry::SIMPLE,
        Maritime::SIGNALING            => Registry::SIMPLE | Registry::PRO,
        Maritime::HYDROGRAPHY          => Registry::SIMPLE | Registry::PRO,
        Maritime::NAVIGATION           => Registry::SIMPLE | Registry::PRO,
        Maritime::FISHING              => Registry::SIMPLE,
        Maritime::SEAMANSHIP_PRACTICES => Registry::SIMPLE,
        Maritime::STEERING             => Registry::SIMPLE,

        Physical::ACROBATICS   => Registry::SIMPLE,
        Physical::ATHLETICS    => Registry::SIMPLE,
        Physical::STEALTH      => Registry::SIMPLE,
        Physical::HORSE_RIDING => Registry::SIMPLE,
        Physical::BURGLARY     => Registry::SIMPLE | Registry::PRO,
        Physical::SWIMMING     => Registry::SIMPLE,
        Physical::SURVIVAL     => Registry::SIMPLE | Registry::PRO,
        Physical::VIGILANCE    => Registry::SIMPLE,

        Social::ACTING             => Registry::SIMPLE,
        Social::KNOWLEDGE_SETTLERS => Registry::SIMPLE,
        Social::KNOWLEDGE_SEAMEN   => Registry::SIMPLE,
        Social::EMPATHY            => Registry::SIMPLE,
        Social::TEACHING           => Registry::SIMPLE,
        Social::ETIQUETTE          => Registry::SIMPLE | Registry::PRO,
        Social::INTIMIDATION       => Registry::SIMPLE,
        Social::GAMING             => Registry::SIMPLE,
        Social::FOREIGN_LANGUAGE   => Registry::SIMPLE,
        Social::LEADERSHIP         => Registry::SIMPLE,
        Social::PERSUASION         => Registry::SIMPLE,
        Social::POLITIC            => Registry::SIMPLE | Registry::PRO,
        Social::SEDUCTION          => Registry::SIMPLE,

        Combat::MELEE          => Registry::MACRO,
        Combat::MELEE_BATON    => Registry::SPECIAL,
        Combat::MELEE_SCIMITAR => Registry::SPECIAL,
        Combat::MELEE_HOOK     => Registry::SPECIAL,
        Combat::MELEE_DAGGER   => Registry::SPECIAL,
        Combat::MELEE_AXE      => Registry::SPECIAL,
        Combat::MELEE_SPEAR    => Registry::SPECIAL,
        Combat::MELEE_RAPIER   => Registry::SPECIAL,
        Combat::MELEE_SABRE    => Registry::SPECIAL,
        Combat::MELEE_WHIP     => Registry::SPECIAL,

        Combat::FLINTLOCK         => Registry::MACRO,
        Combat::FLINTLOCK_MUSKET  => Registry::SPECIAL,
        Combat::FLINTLOCK_PISTOL  => Registry::SPECIAL,
        Combat::FLINTLOCK_GRENADE => Registry::SPECIAL,

        Combat::RANGED              => Registry::MACRO,
        Combat::RANGED_CROSSBOW     => Registry::SPECIAL,
        Combat::RANGED_BOW          => Registry::SPECIAL,
        Combat::RANGED_DAGGER_THROW => Registry::SPECIAL,
        Combat::RANGED_JAVELIN      => Registry::SPECIAL,
        Combat::RANGED_SARBACANE    => Registry::SPECIAL,

        Combat::HAND_FIGHTING => Registry::SIMPLE,
        Combat::FENCING       => Registry::SIMPLE,
        Combat::DODGING       => Registry::SIMPLE,
];