<?php
declare(strict_types=1);

namespace Tests\BlackFlag;

use BlackFlag\Skill;
use BlackFlag\Skill\Domain;
use BlackFlag\Skill\Knowledge;
use PHPUnit\Framework\TestCase;

class SkillTest extends TestCase
{
    public function testHasName(): void
    {
        $skill = new SKill(
            new Domain(Knowledge::BALLISTICS),
            0,
            true
        );
        $this->assertEquals(Knowledge::BALLISTICS, $skill->getName());
    }

    public function testHasLevel(): void
    {
        $skill = new SKill(
            new Domain(Knowledge::BALLISTICS),
            0,
            true
        );
        $this->assertEquals(0, $skill->getLevel());
    }

    public function testCanBeSpecialized(): void
    {
        $skill = new SKill(
            new Domain(Knowledge::BALLISTICS),
            0,
            false
        );
        $this->assertFalse( $skill->isProfessional());

        $skill = new SKill(
            new Domain(Knowledge::EXPERTISE, Knowledge::EXPERTISE_LAW),
            0,
            true
        );
        $this->assertTrue( $skill->isSpecialized());
    }

    public function testCanBeProfessional(): void
    {
        $skill = new SKill(
            new Domain(Knowledge::BALLISTICS),
            0,
            false
        );
        $this->assertFalse( $skill->isProfessional());

        $skill = new SKill(
            new Domain(Knowledge::BALLISTICS),
            0,
            true
        );
        $this->assertTrue( $skill->isProfessional());
    }

    public function testIsConsideredDevelopedOver0(): void
    {
        $skill = new SKill(
            new Domain(Knowledge::BALLISTICS),
            0,
            true
        );
        $this->assertFalse( $skill->isDeveloped());

        $skill = new SKill(
            new Domain(Knowledge::BALLISTICS),
            1,
            true
        );
        $this->assertTrue( $skill->isDeveloped());
    }

    public function testComparesLevelWithDifferentSkills(): void
    {
        $ballistics = new SKill(new Domain(Knowledge::BALLISTICS), 0, true);
        $herbalism = new Skill(new Domain(Knowledge::HERBALISM), 0, true);
        $this->assertTrue($ballistics->equals($herbalism));
        $this->assertFalse($ballistics->higherThan($herbalism));
        $this->assertTrue($ballistics->higherThanOrEqual($herbalism));
        $this->assertFalse($herbalism->lowerThan($ballistics));
        $this->assertTrue($herbalism->lowerThanOrEqual($ballistics));

        $ballistics = new SKill(new Domain(Knowledge::BALLISTICS), 1, true);
        $herbalism = new Skill(new Domain(Knowledge::HERBALISM), 0, true);
        $this->assertFalse($ballistics->equals($herbalism));
        $this->assertTrue($ballistics->higherThan($herbalism));
        $this->assertTrue($ballistics->higherThanOrEqual($herbalism));
        $this->assertTrue($herbalism->lowerThan($ballistics));
        $this->assertTrue($herbalism->lowerThanOrEqual($ballistics));
    }
}
