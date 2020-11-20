<?php
declare(strict_types=1);

namespace Tests\Rpg;

use Rpg\AbstractStatistic;
use PHPUnit\Framework\TestCase;
use Rpg\Exception\InvalidStatisticRequirementsException;
use Rpg\Exception\OutOfRangeStatisticValueException;

class AbstractStatisticTest extends TestCase
{
    public function testHasName(): void
    {
        $stat = new Stat('Test', 5, 1, 10);
        $this->assertEquals('Test', $stat->getName());
        $this->assertEquals(5, $stat->getValue());
    }

    public function testHasNumericValue(): void
    {
        $stat = new Stat('Test', 5, 1, 10);
        $this->assertEquals(5, $stat->getValue());
    }

    public function testSerializesToString(): void
    {
        $stat = new Stat('Test', 5, 1, 10);
        $this->assertEquals('Test: 5', $stat->__ToString());
    }

    public function testRequiresMinimumLowerThanMaximum(): void
    {
        $this->expectException(InvalidStatisticRequirementsException::class);
        $this->expectExceptionMessage(
            sprintf('%s %s requirements are invalid: minimum (%d) cannot be greater than maximum (%d)',
                Stat::class,
                'Test',
                10,
                5
            )
        );

        new Stat('Test', 7, 10, 5);
    }

    public function testEnforcesMinimumRequirements(): void
    {
        $this->expectException(OutOfRangeStatisticValueException::class);
        $this->expectExceptionMessage(
            sprintf('%s "%s" (%d) is under the minimum requirement of %d',
                Stat::class,
                'Test',
                3,
                5
            )
        );

        new Stat('Test', 3, 5, 6);
    }

    public function testEnforcesMaximumRequirements(): void
    {
        $this->expectException(OutOfRangeStatisticValueException::class);
        $this->expectExceptionMessage(
            sprintf('%s "%s" (%d) exceed the maximum requirement of %d',
                Stat::class,
                'Test',
                10,
                6
            )
        );

        new Stat('Test', 10, 5, 6);
    }
}

class Stat extends AbstractStatistic
{
}