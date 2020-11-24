<?php
declare(strict_types=1);

namespace Tests\BlackFlag\PlayableCharacter;

use BlackFlag\Attribute\Primary\Adaptability;
use BlackFlag\Attribute\Primary\Charisma;
use BlackFlag\Attribute\Primary\Constitution;
use BlackFlag\Attribute\Primary\Dexterity;
use BlackFlag\Attribute\Primary\Expression;
use BlackFlag\Attribute\Primary\Knowledge;
use BlackFlag\Attribute\Primary\Perception;
use BlackFlag\Attribute\Primary\Power;
use BlackFlag\Attribute\Primary\Strength;
use BlackFlag\PlayableCharacter\Character;
use BlackFlag\PlayableCharacter\Exception\AttributesMissingException;
use BlackFlag\PlayableCharacter\Exception\AttributesTooLowException;
use BlackFlag\PlayableCharacter\Exception\UnknownAttributesException;
use EventSourcing\Testing\EsTestCase;

final class PrimaryAttributesTest extends EsTestCase
{
    const LIST = [
        Adaptability::name => 5,
        Charisma::name     => 5,
        Constitution::name => 5,
        Dexterity::name    => 5,
        Expression::name   => 5,
        Knowledge::name    => 5,
        Perception::name   => 5,
        Power::name        => 5,
        Strength::name     => 6,
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
    public function testRequiresMandatoryPrimaryAttribute(array $attributes, string $missing): void
    {
        $this->expectException(AttributesMissingException::class);
        $this->expectExceptionMessage(sprintf('Missing attribute: %s', $missing));

        Character::create(
            'John',
            'Doe',
            'Black beard',
            35,
            true,
            $attributes
        );
    }

    public function testProvidesMissingMandatoryPrimaryAttributeList(): void
    {
        $this->expectException(AttributesMissingException::class);
        $this->expectExceptionMessage('Missing attributes: adaptability, charisma');

        Character::create(
            'John',
            'Doe',
            'Black beard',
            35,
            true,
            [
                // 'adaptability' => 5,
                // 'charisma'     => 5,
                Constitution::name => 5,
                Dexterity::name    => 5,
                Expression::name   => 5,
                Knowledge::name    => 5,
                Perception::name   => 5,
                Power::name        => 5,
                Strength::name     => 6,
            ]
        );
    }

    public function testRejectsUnknownPrimaryAttributes():void
    {
        $this->expectException(UnknownAttributesException::class);
        $this->expectExceptionMessage('Unknown attributes: test1, test2');
        Character::create(
            'John',
            'Doe',
            'Black beard',
            35,
            true,
            [
                Adaptability::name => 5,
                Charisma::name     => 5,
                Constitution::name => 5,
                Dexterity::name    => 5,
                Expression::name   => 5,
                Knowledge::name    => 5,
                Perception::name   => 5,
                Power::name        => 5,
                Strength::name     => 6,
                'test1'            => 1,
                'test2'            => 1
            ]
        );

    }

    public function testRequiresAtLast46Points(): void
    {
        $this->expectException(AttributesTooLowException::class);
        $this->expectExceptionMessage('45/46 primary attributes points assigned: missing 1 point');

        Character::create(
            'John',
            'Doe',
            'Black beard',
            35,
            true,
            [
                Adaptability::name => 5,
                Charisma::name     => 5,
                Constitution::name => 5,
                Dexterity::name    => 5,
                Expression::name   => 5,
                Knowledge::name    => 5,
                Perception::name   => 5,
                Power::name        => 5,
                Strength::name     => 5,
            ]
        );
    }
}