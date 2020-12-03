<?php
declare(strict_types=1);

namespace BlackFlag\Skill;

use BlackFlag\Skill;
use BlackFlag\Skill\Exception\SkillException;
use LogicException;

/**
 * @method static Skill BALLISTICS(int $level)
 * @method static Skill CARTOGRAPHY(int $level)
 * @method static Skill TRADE(int $level)
 * @method static Skill EXPERTISE(string $specialization, int $level)
 * @method static Skill HERBALISM(int $level)
 * @method static Skill NAVAL_ENGINEERING(int $level)
 * @method static Skill STEWARDSHIP(int $level)
 * @method static Skill READ_WRITE(int $level)
 * @method static Skill MEDICINE(int $level)
 * @method static Skill RELIGION(string $specialization, int $level)
 * @method static Skill SCIENCES(int $level)
 * @method static Skill TACTIC(int $level)
 *
 * @method static Skill ART(string $specialization, int $level)
 * @method static Skill ARTILLERY_GUN_LAYING(int $level)
 * @method static Skill ARTILLERY_GUN_RELOADING(int $level)
 * @method static Skill CRAFTING(string $specialization, int $level)
 * @method static Skill HUNTING(int $level)
 * @method static Skill SURGERY(int $level)
 * @method static Skill ANIMAL_TRAINING(int $level)
 * @method static Skill FIRST_AID(int $level)
 *
 * @method static Skill SAILING(int $level)
 * @method static Skill SHIP_KNOWLEDGE(int $level)
 * @method static Skill SIGNALING(int $level)
 * @method static Skill HYDROGRAPHY(int $level)
 * @method static Skill NAVIGATION(int $level)
 * @method static Skill FISHING(int $level)
 * @method static Skill SEAMANSHIP_PRACTICES(int $level)
 * @method static Skill STEERING(int $level)
 *
 * @method static Skill ACROBATICS(int $level)
 * @method static Skill ATHLETICS(int $level)
 * @method static Skill STEALTH(int $level)
 * @method static Skill HORSE_RIDING(int $level)
 * @method static Skill BURGLARY(int $level)
 * @method static Skill SWIMMING(int $level)
 * @method static Skill SURVIVAL(int $level)
 * @method static Skill VIGILANCE(int $level)
 *
 * @method static Skill ACTING(int $level)
 * @method static Skill KNOWLEDGE_SETTLERS(string $specialization, int $level)
 * @method static Skill KNOWLEDGE_SEAMEN(string $specialization, int $level)
 * @method static Skill EMPATHY(int $level)
 * @method static Skill TEACHING(int $level)
 * @method static Skill ETIQUETTE(int $level)
 * @method static Skill INTIMIDATION(int $level)
 * @method static Skill GAMING(int $level)
 * @method static Skill FOREIGN_LANGUAGE(string $specialization, int $level)
 * @method static Skill LEADERSHIP(int $level)
 * @method static Skill PERSUASION(int $level)
 * @method static Skill POLITIC(int $level)
 * @method static Skill SEDUCTION(int $level)
 *
 * @method static Skill MELEE(string $specialization, int $level)
 * @method static Skill FLINTLOCK(string $specialization, int $level)
 * @method static Skill RANGED(string $specialization, int $level)
 * @method static Skill HAND_FIGHTING(int $level)
 * @method static Skill FENCING(int $level)
 * @method static Skill DODGING(int $level)
 */
trait Factory
{
    /**
     * Assisting statically (hard coded) Skill instantiation
     *
     * @param array<mixed> $arguments
     *
     * @example Skill::BALLISTICS(4)
     * @example Skill::EXPERTISE(Skill\Knowledge::EXPERTISE_LAW, 3)
     */
    public static function __callStatic(string $id, array $arguments): Skill
    {
        if (!defined(sprintf('%s::%s', Registry::class, $id))) {
            throw new SkillException(sprintf('Unknown skill constant "%s::%s"', Registry::class, $id));
        }

        $name = constant(sprintf('%s::%s', Registry::class, $id));
        $rule = Registry::getDefaultRule($name);
        $name = $rule->getName();
        $specialization = null;
        $isProfessional = $rule->isProfessional();
        $level = $arguments[0];

        if ($rule->isSpecialization()) {
            throw new LogicException('Unable to create skill directly from specialization');
        }

        if ($rule->isSpecialized()) {
            $specialization = $arguments[0];
            $specializationRule = Registry::getDefaultRule($arguments[0]);
            $isProfessional = $specializationRule->isProfessional();
            $level = $arguments[1];
        }

        return new Skill(
            new Domain($name, $specialization),
            $level,
            $isProfessional
        );
    }
}