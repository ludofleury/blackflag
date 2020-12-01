<?php
declare(strict_types=1);

namespace Tests\BlackFlag\Resolution\Dice;

use BlackFlag\Resolution\Dice;
use BlackFlag\Resolution\Dice\Factory;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    /** @return array<string, array<string|int>> */
    public function providesOfficialDices(): array
    {
        return [
            'D10'  => ['D10', 10],
            'D12'  => ['D12', 12],
            'D20'  => ['D20', 20],
            'D100' => ['D100',100],
        ];
    }

    /**
     * @dataProvider providesOfficialDices
     */
    public function testProvidesOfficialActionDices(string $type, int $sides): void
    {
        /** @var Factory|object $behavior */
        $behavior = $this->getObjectForTrait(Factory::class);

        /** @var callable $callable */
        $callable = sprintf('%s::%s', get_class($behavior), $type);

        /** @var Dice $dice */
        $dice = call_user_func($callable);
        $this->assertInstanceOf(Dice::class, $dice);
        $this->assertEquals($sides, $dice->getSides());
    }
}
