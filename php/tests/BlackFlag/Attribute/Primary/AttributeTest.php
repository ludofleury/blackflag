<?php
declare(strict_types=1);

namespace Tests\BlackFlag\Attribute\Primary;

use BlackFlag\Attribute\Primary;
use BlackFlag\Attribute\Primary\Adaptability;
use BlackFlag\Attribute\Primary\Charisma;
use BlackFlag\Attribute\Primary\Constitution;
use BlackFlag\Attribute\Primary\Dexterity;
use BlackFlag\Attribute\Primary\Expression;
use BlackFlag\Attribute\Primary\Knowledge;
use BlackFlag\Attribute\Primary\Perception;
use BlackFlag\Attribute\Primary\Power;
use BlackFlag\Attribute\Primary\Strength;
use PHPUnit\Framework\TestCase;
use Rpg\AbstractStatistic;
use Rpg\Exception\OutOfRangeStatisticValueException;
use Rpg\Statistic;

final class AttributeTest extends TestCase
{
    const LIST = [
        'adaptability' => ['name' => 'adaptability', 'class' => Adaptability::class],
        'charisma'     => ['name' => 'charisma', 'class' => Charisma::class],
        'constitution' => ['name' => 'constitution', 'class' => Constitution::class],
        'dexterity'    => ['name' => 'dexterity', 'class' => Dexterity::class],
        'expression'   => ['name' => 'expression', 'class' => Expression::class],
        'knowledge'    => ['name' => 'knowledge', 'class' => Knowledge::class],
        'perception'   => ['name' => 'perception', 'class' => Perception::class],
        'power'        => ['name' => 'power', 'class' => Power::class],
        'strength'     => ['name' => 'strength', 'class' => Strength::class],
    ];

    /**
     * @return array<string, array<string, string|int>>
     */
    public function provideAttributes(): array
    {
        return self::LIST;
    }

    /**
     * @dataProvider provideAttributes
     * @param class-string $class
     */
    public function testIsPrimary(string $name, string $class): void
    {
        $this->assertInstanceOf(Primary::class, new $class(5));
        $this->assertEquals($name, $class::name);
    }

    /**
     * @dataProvider provideAttributes
     * @param class-string $class
     */
    public function testIsRpgStatistic(string $name, string $class): void
    {
        $this->assertInstanceOf(Statistic::class, new $class(5));
        $this->assertEquals($name, (new $class(5))->getName());
    }

    /**
     * @dataProvider provideAttributes
     * @param class-string $class
     */
    public function testCannotBeLowerThan2(string $name, string $class): void
    {
        $this->expectException(OutOfRangeStatisticValueException::class);
        $this->expectExceptionMessage('1 is too low: minimum 2');

        new $class(1);
    }

    /**
     * @dataProvider provideAttributes
     * @param class-string $class
     */
    public function testCannotBeGreaterThan9(string $name, string $class): void
    {
        $this->expectException(OutOfRangeStatisticValueException::class);
        $this->expectExceptionMessage('10 is too high: maximum 9');

        new $class(10);
    }
}