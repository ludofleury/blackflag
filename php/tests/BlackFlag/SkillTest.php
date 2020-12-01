<?php
declare(strict_types=1);

namespace Tests\BlackFlag;

use BlackFlag\Skill;
use BlackFlag\Skill\Exception\SkillException;
use DomainException;
use LogicException;
use PHPUnit\Framework\TestCase;

class SkillTest extends TestCase
{
    public function testHasName(): void
    {
        $skill = new Skill(Skill\Knowledge::BALLISTICS, null, 0, true);
        $this->assertEquals(Skill\Knowledge::BALLISTICS, $skill->getName());
    }

    public function testHasLevel(): void
    {
        $skill = new Skill(Skill\Knowledge::BALLISTICS, null, 0, true);
        $this->assertEquals(0, $skill->getLevel());
    }

    public function testCanBeProfessional(): void
    {
        $skill = new Skill(Skill\Knowledge::BALLISTICS, null, 0, true);
        $this->asserttrue( $skill->isProfessional());
    }

    public function testIsConsideredDevelopedOver0(): void
    {
        $skill = new Skill(Skill\Knowledge::BALLISTICS, null, 0, true);
        $this->assertFalse( $skill->isDeveloped());

        $skill = new Skill(Skill\Knowledge::BALLISTICS, null, 1, true);
        $this->assertTrue( $skill->isDeveloped());
    }

    public function testCanBeSpecialized(): void
    {
        $skill = new Skill(Skill\Knowledge::BALLISTICS, null, 1, true);
        $this->assertFalse($skill->isSpecialized());

        $skill = new Skill(Skill\Knowledge::EXPERTISE, Skill\Knowledge::EXPERTISE_LAW, 1, true);
        $this->assertTrue($skill->isSpecialized());
    }

    public function testRejectsUnknownSkill(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Unknown skill "random"');

        new Skill('random', null, 1, false);
    }

    public function testRejectsDirectSpecialization(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('"law" is a specialization, instantiate with BlackFlag\\Skill("[main skill]", "law", ...) instead');

        new Skill(Skill\Knowledge::EXPERTISE_LAW, null, 1, false);
    }

    public function testRequiresMinimumLevel0(): void
    {
        $this->expectException(SkillException::class);
        $this->expectExceptionMessage('"ballistics": -1 is too low, minimum 0');

        new Skill(Skill\Knowledge::BALLISTICS, null, -1, true);
    }

    public function testRequiresMaximumLevel7(): void
    {
        $this->expectException(SkillException::class);
        $this->expectExceptionMessage('"ballistics": 8 is too high, maximum 7');

        new Skill(Skill\Knowledge::BALLISTICS, null, 8, true);
    }

    public function testComparesLevelWithDifferentSkills(): void
    {
        $ballistics = new Skill(Skill\Knowledge::BALLISTICS, null, 0, true);
        $herbalism = new Skill(Skill\Knowledge::HERBALISM, null, 0, true);
        $this->assertTrue($ballistics->equals($herbalism));
        $this->assertFalse($ballistics->higherThan($herbalism));
        $this->assertTrue($ballistics->higherThanOrEqual($herbalism));
        $this->assertFalse($herbalism->lowerThan($ballistics));
        $this->assertTrue($herbalism->lowerThanOrEqual($ballistics));

        $ballistics = new Skill(Skill\Knowledge::BALLISTICS, null, 1, true);
        $herbalism = new Skill(Skill\Knowledge::HERBALISM, null, 0, true);
        $this->assertFalse($ballistics->equals($herbalism));
        $this->assertTrue($ballistics->higherThan($herbalism));
        $this->assertTrue($ballistics->higherThanOrEqual($herbalism));
        $this->assertTrue($herbalism->lowerThan($ballistics));
        $this->assertTrue($herbalism->lowerThanOrEqual($ballistics));
    }


}
