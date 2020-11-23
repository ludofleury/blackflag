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

final class AttributeTest extends TestCase
{
    const LIST = [
        'adaptability' => ['name' => 'adaptability', 'class' => Adaptability::class, 'min' => 3, 'max' => 8],
        'charisma'     => ['name' => 'charisma', 'class' => Charisma::class, 'min' => 3, 'max' => 8],
        'constitution' => ['name' => 'constitution', 'class' => Constitution::class, 'min' => 5, 'max' => 8],
        'dexterity'    => ['name' => 'dexterity', 'class' => Dexterity::class, 'min' => 3, 'max' => 8],
        'expression'   => ['name' => 'expression', 'class' => Expression::class, 'min' => 3, 'max' => 8],
        'knowledge'    => ['name' => 'knowledge', 'class' => Knowledge::class, 'min' => 3, 'max' => 8],
        'perception'   => ['name' => 'perception', 'class' => Perception::class, 'min' => 3, 'max' => 8],
        'power'        => ['name' => 'power', 'class' => Power::class, 'min' => 3, 'max' => 8],
        'strength'     => ['name' => 'strength', 'class' => Strength::class, 'min' => 3, 'max' => 8],
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
    public function testIsPrimary(string $name, string $class, int $min, int $max): void
    {
        $this->assertInstanceOf(Primary::class, new $class(5));
    }

    /**
     * @dataProvider provideAttributes
     * @param class-string $class
     */
    public function testIsRpgStatistic(string $name, string $class, int $min, int $max): void
    {
        $this->assertInstanceOf(AbstractStatistic::class, new $class(5));
    }

    /**
     * @dataProvider provideAttributes
     * @param class-string $class
     */
    public function testHasShortName(string $name, string $class, int $min, int $max): void
    {
        $this->assertEquals($name, $class::name);
        $this->assertEquals($name, (new $class(5))->getName());
    }

    /**
     * @dataProvider provideAttributes
     * @param class-string $class
     */
    public function testEnforcesDefaultMinimumRequirement(string $name, string $class, int $min, int $max): void
    {
        $this->expectException(OutOfRangeStatisticValueException::class);
        $this->expectExceptionMessage(
            sprintf(
                '%d is under the minimum requirement of %d',
                $min-1,
                $min
            )
        );

        new $class($min-1);
    }

    /**
     * @dataProvider provideAttributes
     * @param class-string $class
     */
    public function testEnforcesDefaultMaximumRequirement(string $name, string $class, int $min, int $max): void
    {
        $this->expectException(OutOfRangeStatisticValueException::class);
        $this->expectExceptionMessage(
            sprintf('9 exceed the maximum requirement of %d', $max)
        );

        new $class($max+1);
    }

    /**
     * @dataProvider provideAttributes
     * @param class-string $class
     */
    public function testAllowsCustomRequirements(string $name, string $class, int $min, int $max): void
    {
        /** @var AbstractStatistic $attribute */
        $attribute = new $class(1, 0, 3);
        $this->assertEquals(1, $attribute->getValue());

        $attribute = new $class(10, 2, 11);
        $this->assertEquals(10, $attribute->getValue());
    }
}