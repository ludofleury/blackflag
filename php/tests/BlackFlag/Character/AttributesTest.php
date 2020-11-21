<?php
declare(strict_types=1);

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

    /**
     * @return array<string, array<int, array<string, int>|string>>
     */
    public function provideMissingAttributes(): array
    {
        $required = [];
        foreach (self::LIST as $name => $value) {
            $missingAttributeName = $name;
            $attributes = self::LIST;
            unset($attributes[$name]);
            $required[$missingAttributeName] = [$attributes, $missingAttributeName];
        }

        return $required;
    }

    /**
     * @dataProvider provideMissingAttributes
     * @param array<string, int> $attributes
     */
    public function testRequiresMandatoryAttribute(array $attributes, string $missing): void
    {
        $this->expectException(AttributesMissingException::class);
        $this->expectExceptionMessage(sprintf('Missing attributes: %s', $missing));

        Character::create(
            'John',
            'Doe',
            'Black beard',
            35,
            true,
            $attributes
        );
    }

    public function testRequiresAtLast46Points(): void
    {
        $this->expectException(AttributesTooLowException::class);
        $this->expectExceptionMessage(sprintf('46 attribute points minimum requirement: %d is too low', 45));

        Character::create(
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