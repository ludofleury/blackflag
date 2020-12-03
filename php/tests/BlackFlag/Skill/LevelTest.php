<?php
declare(strict_types=1);

namespace Tests\BlackFlag\Skill;

use BlackFlag\Skill\Level;
use BlackFlag\Skill\Exception\SkillException;
use Generator;
use PHPUnit\Framework\TestCase;

class LevelTest extends TestCase
{
    /** @return Generator<array<int, int>> */
    public function providesValidValue(): Generator
    {
        foreach (range(0,7) as $level) {
            yield [$level => $level];
        }
    }
    
    /** @dataProvider providesValidValue */
    public function testAccepts0to7(int $value): void
    {
        $this->assertEquals($value, (new Level($value))->getValue());
    }

    public function testRequiresMinimum0(): void
    {
        $this->expectException(SkillException::class);
        $this->expectExceptionMessage('Level -1 is too low, minimum 0');

        new Level(-1);
    }

    public function testRequiresMaximum7(): void
    {
        $this->expectException(SkillException::class);
        $this->expectExceptionMessage('Level 8 is too high, maximum 7');

        new Level(8);
    }

    public function testComparesToOtherLevel(): void
    {
        $levelA = new Level(0);
        $levelB = new Level(0);
        $this->assertTrue($levelA->equals($levelB));
        $this->assertFalse($levelA->higherThan($levelB));
        $this->assertTrue($levelA->higherThanOrEqual($levelB));
        $this->assertFalse($levelB->lowerThan($levelA));
        $this->assertTrue($levelB->lowerThanOrEqual($levelA));

        $level1 = new Level(1);
        $level0 = new Level(0);
        $this->assertFalse($level1->equals($level0));
        $this->assertTrue($level1->higherThan($level0));
        $this->assertTrue($level1->higherThanOrEqual($level0));
        $this->assertTrue($level0->lowerThan($level1));
        $this->assertTrue($level0->lowerThanOrEqual($level1));
    }
}