<?php
declare(strict_types=1);

namespace Tests\Rpg;

use Rpg\AbstractStatistic;
use PHPUnit\Framework\TestCase;
use Rpg\Exception\InvalidStatisticRequirementsException;
use Rpg\Exception\OutOfRangeStatisticValueException;

class AbstractStatisticTest extends TestCase
{
    public function testHasNumericValue(): void
    {
        $stat = new Stat(5, 1, 10);
        $this->assertEquals(5, $stat->getValue());
    }

    public function testRequiresMinimumLowerThanMaximum(): void
    {
        $this->expectException(InvalidStatisticRequirementsException::class);
        $this->expectExceptionMessage(
            sprintf('minimum %d cannot be greater than maximum %d',
                10,
                5
            )
        );

        new Stat(7, 10, 5);
    }

    public function testEnforcesMinimumRequirements(): void
    {
        $this->expectException(OutOfRangeStatisticValueException::class);
        $this->expectExceptionMessage(
            sprintf('%d is too low: minimum %d',
                3,
                5
            )
        );

        new Stat(3, 5, 6);
    }

    public function testEnforcesMaximumRequirements(): void
    {
        $this->expectException(OutOfRangeStatisticValueException::class);
        $this->expectExceptionMessage(
            sprintf('%d is too high: maximum %d',
                10,
                6
            )
        );

        new Stat(10, 5, 6);
    }
}

class Stat extends AbstractStatistic
{
    public function getName(): string
    {
        return 'test';
    }
}