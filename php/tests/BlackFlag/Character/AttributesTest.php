<?php

namespace Tests\BlackFlag\Character;

use BlackFlag\Character\Character;
use BlackFlag\Character\Exception\AttributesMissingException;
use BlackFlag\Character\Exception\AttributesTooLowException;
use EventSourcing\Testing\EsTestCase;

final class AttributesTest extends EsTestCase
{
    const LIST = [
        'adaptability' => 5,
        'charisma'     => 5,
        'constitution' => 5,
        'dexterity'    => 5,
        'expression'   => 5,
        'knowledge'    => 5,
        'perception'   => 5,
        'power'        => 5,
        'strength'     => 6,
    ];

    public function provideMissingAttributes()
    {
        $required = [];
        foreach (self::LIST as $name => $value) {
            $missing = self::LIST;
            unset($missing[$name]);
            $required[$name] = [$missing, $name];
        }

        return $required;
    }

    /**
     * @dataProvider provideMissingAttributes
     */
    public function testRequiresMandatoryAttribute(array $attributes, string $missing)
    {
        $this->expectException(AttributesMissingException::class);
        $this->expectExceptionMessage(sprintf('Missing attributes: %s', $missing));

        $john = Character::create(
            'John',
            'Doe',
            'Black beard',
            35,
            true,
            $attributes
        );
    }

    public function testRequires46AttributesPointsMinimum()
    {
        $this->expectException(AttributesTooLowException::class);
        $this->expectExceptionMessage(sprintf('46 attribute points minimum requirement: %d is too low', 45));

        $john = Character::create(
            'John',
            'Doe',
            'Black beard',
            35,
            true,
            [
                'adaptability' => 5,
                'charisma'     => 5,
                'constitution' => 5,
                'dexterity'    => 5,
                'expression'   => 5,
                'knowledge'    => 5,
                'perception'   => 5,
                'power'        => 5,
                'strength'     => 5,
            ]
        );
    }
}