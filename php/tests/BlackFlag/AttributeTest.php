<?php
declare(strict_types=1);

namespace Tests\BlackFlag;

use BlackFlag\Attribute;
use BlackFlag\Attribute\Characteristic;
use BlackFlag\Attribute\Exception\InvalidLevel;
use Generator;
use PHPUnit\Framework\TestCase;

final class AttributeTest extends TestCase
{
    /** @return Generator<array<int, int>> */
    public function providesValidLevel(): Generator
    {
        foreach (range(2,8) as $level) {
            yield [$level => $level];
        }
    }

    public function testHasName(): void
    {
        $attribute = new Attribute(
            new Characteristic(Characteristic::ADAPTABILITY),
            2
        );
        $this->assertEquals(Characteristic::ADAPTABILITY, $attribute->getName());
    }

    public function testHasLevel(): void
    {
        $attribute = new Attribute(
            new Characteristic(Characteristic::ADAPTABILITY),
            2
        );
        $this->assertEquals(2, $attribute->getLevel());
    }

    /** @dataProvider providesValidLevel */
    public function testRangesFromLevel2to8(int $level): void
    {
        $attribute = new Attribute(
            new Characteristic(Characteristic::ADAPTABILITY),
            $level
        );
        $this->assertEquals($level, $attribute->getLevel());
    }

    public function testRequiresMinimumLevel2(): void
    {
        $this->expectException(InvalidLevel::class);
        $this->expectExceptionMessage('Level 1 is too low, minimum 2');

        $attribute = new Attribute(
            new Characteristic(Characteristic::ADAPTABILITY),
            1
        );
    }

    public function testRequiresMaximumLevel8(): void
    {
        $this->expectException(InvalidLevel::class);
        $this->expectExceptionMessage('Level 9 is too high, maximum 8');

        $attribute = new Attribute(
            new Characteristic(Characteristic::ADAPTABILITY),
            9
        );
    }
}