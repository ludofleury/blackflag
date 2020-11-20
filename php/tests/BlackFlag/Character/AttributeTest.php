<?php

namespace Tests\BlackFlag\Character;

use BlackFlag\Character\Attribute;
use BlackFlag\Character\Exception\UnknownAttributeException;
use DomainException;
use EventSourcing\Testing\EsTestCase;

final class AttributeTest extends EsTestCase
{
    const LIST = [
        'strength'     => ['type' => 'strength', 'min' => 3, 'max' => 8],
        'dexterity'    => ['type' => 'dexterity', 'min' => 3, 'max' => 8],
        'adaptability' => ['type' => 'adaptability', 'min' => 3, 'max' => 8],
        'knowledge'    => ['type' => 'knowledge', 'min' => 3, 'max' => 8],
        'power'        => ['type' => 'power', 'min' => 3, 'max' => 8],
        'perception'   => ['type' => 'perception', 'min' => 3, 'max' => 8],
        'charisma'     => ['type' => 'charisma', 'min' => 3, 'max' => 8],
        'expression'   => ['type' => 'expression', 'min' => 3, 'max' => 8],
        'constitution' => ['type' => 'constitution', 'min' => 5, 'max' => 8],
    ];

    public function provideAttributes(): array
    {
        return self::LIST;
    }

    public function testRejectsUnknownAttribute(): void
    {
        $this->expectException(UnknownAttributeException::class);
        $this->expectExceptionMessage('Unsupported attribute "toto"');

        new Attribute('toto', 4);
    }

    /**
     * @dataProvider provideAttributes
     */
    public function testHandlesSupportedAttribute(string $name): void
    {
        $sut = new Attribute($name, 5);
        $this->assertSame($sut->getName(), $name);
        $this->assertSame($sut->getValue(), 5);
    }

    /**
     * @dataProvider provideAttributes
     */
    public function testEnforcesMinimumRequirement(string $name, int $min, int $max): void
    {
        $invalidValue = ($name === 'constitution') ? 4 : 2;

        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            sprintf(
                'BlackFlag\Character\Attribute "%s" (%d) is under the minimum requirement of %d',
                $name,
                $invalidValue,
                $min
            )
        );

        new Attribute($name, $invalidValue);
    }

    /**
     * @dataProvider provideAttributes
     */
    public function testEnforcesMaximumRequirement(string $name, int $min, int $max): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(
            sprintf('BlackFlag\Character\Attribute "%s" (9) exceed the maximum requirement of %d', $name, $max)
        );

        new Attribute($name, 9);
    }
}