<?php
declare(strict_types=1);

namespace Tests\Rpg\Dice;

use DomainException;
use Rpg\Dice\AnyDice;
use PHPUnit\Framework\TestCase;
use Rpg\Dice\RandomGenerator;
use RuntimeException;

class AnyDiceTest extends TestCase
{
    public function testGeneratesRandomInteger(): void
    {
        $generator = $this->getMockForAbstractClass(RandomGenerator::class);
        $generator->expects($this->once())
            ->method('generate')
            ->with($this->equalTo(2))
            ->willReturn(2)
        ;

        $dice = new AnyDice(2, $generator);
        $this->assertEquals(2, $dice->getResult());;
    }

    public function testRequiresAtLeast2Sides(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Dice must have at least 2 sides');

        new AnyDice(1);
    }

    public function testHasSides(): void
    {
        $dice = new AnyDice(2);
        $this->assertEquals(2, $dice->getSides());
    }
}