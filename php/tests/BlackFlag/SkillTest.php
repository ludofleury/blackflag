<?php
declare(strict_types=1);

namespace Tests\BlackFlag;

use BlackFlag\Skill;
use BlackFlag\Skill\Domain;
use BlackFlag\Skill\Domain\Knowledge;
use BlackFlag\Skill\Exception\SkillException;
use Generator;
use PHPUnit\Framework\TestCase;

class SkillTest extends TestCase
{
    /** @return Generator<array<int, int>> */
    public function providesValidLevel(): Generator
    {
        foreach (range(0,7) as $level) {
            yield [$level => $level];
        }
    }

    public function testHasName(): void
    {
        $skill = new Skill(
            new Domain(Knowledge::BALLISTICS),
            0,
            true
        );
        $this->assertEquals(Knowledge::BALLISTICS, $skill->getName());
    }

    public function testHasLevel(): void
    {
        $skill = new Skill(
            new Domain(Knowledge::BALLISTICS),
            0,
            true
        );
        $this->assertEquals(0, $skill->getLevel());
    }

    public function testCanBeSpecialized(): void
    {
        $skill = new Skill(
            new Domain(Knowledge::BALLISTICS),
            0,
            false
        );
        $this->assertFalse( $skill->isProfessional());

        $skill = new Skill(
            new Domain(Knowledge::EXPERTISE, Knowledge::EXPERTISE_LAW),
            0,
            true
        );
        $this->assertTrue( $skill->isSpecialized());
    }

    public function testCanBeProfessional(): void
    {
        $skill = new Skill(
            new Domain(Knowledge::BALLISTICS),
            0,
            false
        );
        $this->assertFalse( $skill->isProfessional());

        $skill = new Skill(
            new Domain(Knowledge::BALLISTICS),
            0,
            true
        );
        $this->assertTrue( $skill->isProfessional());
    }

    public function testIsConsideredDevelopedOver0(): void
    {
        $skill = new Skill(
            new Domain(Knowledge::BALLISTICS),
            0,
            true
        );
        $this->assertFalse( $skill->isDeveloped());

        $skill = new Skill(
            new Domain(Knowledge::BALLISTICS),
            1,
            true
        );
        $this->assertTrue( $skill->isDeveloped());
    }

    /** @dataProvider providesValidLevel */
    public function testRangesFromLevel0To7(int $level): void
    {
        $skill = new Skill(
            new Domain(Knowledge::BALLISTICS),
            $level,
            false
        );
        $this->assertEquals($level, $skill->getLevel());
    }

    public function testRequiresMinimumLevel0(): void
    {
        $this->expectException(SkillException::class);
        $this->expectExceptionMessage('Level -1 is too low, minimum 0');

        $skill = new Skill(
            new Domain(Knowledge::BALLISTICS),
            -1,
            false
        );
    }

    public function testRequiresMaximumLevel7(): void
    {
        $this->expectException(SkillException::class);
        $this->expectExceptionMessage('Level 8 is too high, maximum 7');

        $skill = new Skill(
            new Domain(Knowledge::BALLISTICS),
            8,
            false
        );
    }

    public function testComparesLevelWithDifferentSkills(): void
    {
        $ballistics = new Skill(new Domain(Knowledge::BALLISTICS), 0, true);
        $herbalism = new Skill(new Domain(Knowledge::HERBALISM), 0, true);
        $this->assertTrue($ballistics->equals($herbalism));
        $this->assertFalse($ballistics->higherThan($herbalism));
        $this->assertTrue($ballistics->higherThanOrEqual($herbalism));
        $this->assertFalse($herbalism->lowerThan($ballistics));
        $this->assertTrue($herbalism->lowerThanOrEqual($ballistics));

        $ballistics = new Skill(new Domain(Knowledge::BALLISTICS), 1, true);
        $herbalism = new Skill(new Domain(Knowledge::HERBALISM), 0, true);
        $this->assertFalse($ballistics->equals($herbalism));
        $this->assertTrue($ballistics->higherThan($herbalism));
        $this->assertTrue($ballistics->higherThanOrEqual($herbalism));
        $this->assertTrue($herbalism->lowerThan($ballistics));
        $this->assertTrue($herbalism->lowerThanOrEqual($ballistics));
    }
}
