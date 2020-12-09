<?php
declare(strict_types=1);

namespace Tests\BlackFlag\Attribute;

use BlackFlag\Attribute\Exception\InvalidLevel;
use BlackFlag\Attribute\Level;
use Generator;
use PHPUnit\Framework\TestCase;

class LevelTest extends TestCase
{
    /** @return Generator<array<int, int>> */
    public function providesValidValue(): Generator
    {
        foreach (range(2,8) as $level) {
            yield [$level => $level];
        }
    }

    /** @dataProvider providesValidValue */
    public function testAccepts2to8(int $value): void
    {
        $this->assertEquals($value, (new Level($value))->getValue());
    }

    public function testRequiresMinimum2(): void
    {
        $this->expectException(InvalidLevel::class);
        $this->expectExceptionMessage('Level 1 is too low, minimum 2');

        new Level(1);
    }

    public function testRequiresMaximum8(): void
    {
        $this->expectException(InvalidLevel::class);
        $this->expectExceptionMessage('Level 9 is too high, maximum 8');

        new Level(9);
    }

    public function testComparesToOtherLevel(): void
    {
        $levelA = new Level(3);
        $levelB = new Level(3);
        $this->assertTrue($levelA->equals($levelB));
        $this->assertFalse($levelA->higherThan($levelB));
        $this->assertTrue($levelA->higherThanOrEqual($levelB));
        $this->assertFalse($levelB->lowerThan($levelA));
        $this->assertTrue($levelB->lowerThanOrEqual($levelA));

        $level1 = new Level(3);
        $level0 = new Level(2);
        $this->assertFalse($level1->equals($level0));
        $this->assertTrue($level1->higherThan($level0));
        $this->assertTrue($level1->higherThanOrEqual($level0));
        $this->assertTrue($level0->lowerThan($level1));
        $this->assertTrue($level0->lowerThanOrEqual($level1));
    }
}