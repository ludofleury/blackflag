<?php
declare(strict_types=1);

namespace Tests\BlackFlag\Resolution;

use BlackFlag\Resolution\Dice;
use BlackFlag\Resolution\Facility;
use DomainException;
use PHPUnit\Framework\TestCase;
use Rpg\Dice\RandomGenerator;

class DiceTest extends TestCase
{
    public function testSupportsD10D12D20D100(): void
    {
        $this->assertInstanceOf(Dice::class, new Dice(10));
        $this->assertInstanceOf(Dice::class, new Dice(12));
        $this->assertInstanceOf(Dice::class, new Dice(20));
        $this->assertInstanceOf(Dice::class, new Dice(100));
    }

    public function testRejectsUnofficialActionDice(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Unsupported action dice "D30"');

        new Dice(30);
    }

    public function testIsCriticalSuccessOn1(): void
    {
        $generator = new class() implements RandomGenerator {
            public function generate(int $max): int
            {
                return 1;
            }
        };
        $dice = new Dice(10, $generator);
        $this->assertTrue($dice->isCriticalSuccess());
        $this->assertFalse($dice->isRegularSuccessFor(new Facility(2)));
    }

    public function testIsNotARegularSuccessOn1(): void
    {
        $generator = new class() implements RandomGenerator {
            public function generate(int $max): int
            {
                return 1;
            }
        };
        $dice = new Dice(10, $generator);
        $this->assertFalse($dice->isRegularSuccessFor(new Facility(2)));
    }

    public function testIsCriticalFailureOn10OrMore(): void
    {
        $generator = new class() implements RandomGenerator {
            public function generate(int $max): int
            {
                return 10;
            }
        };
        $dice = new Dice(10, $generator);
        $this->assertTrue($dice->isCriticalFailure());

        $generator = new class() implements RandomGenerator {
            public function generate(int $max): int
            {
                return 11;
            }
        };
        $dice = new Dice(10, $generator);
        $this->assertTrue($dice->isCriticalFailure());

    }

    public function testIsRegularSuccessWhenEqualOrLowerThanFacility(): void
    {
        $generator = new class() implements RandomGenerator {
            public function generate(int $max): int
            {
                return 7;
            }
        };
        $dice = new Dice(10, $generator);
        $this->assertFalse($dice->isRegularSuccessFor(new Facility(6)));
        $this->assertTrue($dice->isRegularSuccessFor(new Facility(7)));
        $this->assertTrue($dice->isRegularSuccessFor(new Facility(8)));
    }

    public function testIsAlwaysAFailureOn10OrMoreNoMatterTheFacility(): void
    {
        $generator = new class() implements RandomGenerator {
            public function generate(int $max): int
            {
                return 10;
            }
        };
        $dice = new Dice(10, $generator);
        $this->assertFalse($dice->isRegularSuccessFor(new Facility(11)));
    }
}

