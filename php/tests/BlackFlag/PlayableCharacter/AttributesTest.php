<?php
declare(strict_types=1);

namespace Tests\BlackFlag\PlayableCharacter;

use BlackFlag\Attribute\Characteristic;
use BlackFlag\PlayableCharacter\Character;
use BlackFlag\PlayableCharacter\Exception\CharacteristicsMissingException;
use BlackFlag\PlayableCharacter\Exception\CharacteristicsTooLowException;
use BlackFlag\PlayableCharacter\Exception\UnknownCharacteristicsException;
use EventSourcing\Testing\EsTestCase;

final class AttributesTest extends EsTestCase
{
    const LIST = [
        Characteristic::ADAPTABILITY => 5,
        Characteristic::CHARISMA     => 5,
        Characteristic::CONSTITUTION => 5,
        Characteristic::DEXTERITY    => 5,
        Characteristic::EXPRESSION   => 5,
        Characteristic::KNOWLEDGE    => 5,
        Characteristic::PERCEPTION   => 5,
        Characteristic::POWER        => 5,
        Characteristic::STRENGTH     => 6,
    ];

    /**
     * @return array<string, array<int, array<string, int>|string>>
     */
    public function provideMissingCharacteristics(): array
    {
        $required = [];
        foreach (self::LIST as $name => $value) {
            $missingCharacteristicName = $name;
            $characteristics = self::LIST;
            unset($characteristics[$name]);
            $required[$missingCharacteristicName] = [$characteristics, $missingCharacteristicName];
        }

        return $required;
    }

    /**
     * @dataProvider provideMissingCharacteristics
     * @param array<string, int> $characteristics
     */
    public function testRequiresMandatoryPrimaryCharacteristic(array $characteristics, string $missing): void
    {
        $this->expectException(CharacteristicsMissingException::class);
        $this->expectExceptionMessage(sprintf('Missing characteristic: %s', $missing));

        Character::create(
            'John',
            'Doe',
            'Black beard',
            35,
            true,
            $characteristics, // @phpstan-ignore-line
            [],
        );
    }

    public function testProvidesMissingMandatoryPrimaryCharacteristicList(): void
    {
        $this->expectException(CharacteristicsMissingException::class);
        $this->expectExceptionMessage('Missing characteristics: adaptability, charisma');

        Character::create(
            'John',
            'Doe',
            'Black beard',
            35,
            true,
            [ // @phpstan-ignore-line
                // 'adaptability' => 5,
                // 'charisma'     => 5,
                Characteristic::CONSTITUTION => 5,
                Characteristic::DEXTERITY    => 5,
                Characteristic::EXPRESSION   => 5,
                Characteristic::KNOWLEDGE    => 5,
                Characteristic::PERCEPTION   => 5,
                Characteristic::POWER        => 5,
                Characteristic::STRENGTH     => 6,
            ],
            []
        );
    }

    public function testRejectsUnknownPrimaryCharacteristics():void
    {
        $this->expectException(UnknownCharacteristicsException::class);
        $this->expectExceptionMessage('Unknown characteristics: test1, test2');
        Character::create(
            'John',
            'Doe',
            'Black beard',
            35,
            true,
            [
                Characteristic::ADAPTABILITY => 5,
                Characteristic::CHARISMA     => 5,
                Characteristic::CONSTITUTION => 5,
                Characteristic::DEXTERITY    => 5,
                Characteristic::EXPRESSION   => 5,
                Characteristic::KNOWLEDGE    => 5,
                Characteristic::PERCEPTION   => 5,
                Characteristic::POWER        => 5,
                Characteristic::STRENGTH     => 6,
                'test1'            => 1,
                'test2'            => 1
            ],
            []
        );

    }

    public function testRequiresAtLast46Points(): void
    {
        $this->expectException(CharacteristicsTooLowException::class);
        $this->expectExceptionMessage('45/46 characteristic points assigned: missing 1 point');

        Character::create(
            'John',
            'Doe',
            'Black beard',
            35,
            true,
            [
                Characteristic::ADAPTABILITY => 5,
                Characteristic::CHARISMA     => 5,
                Characteristic::CONSTITUTION => 5,
                Characteristic::DEXTERITY    => 5,
                Characteristic::EXPRESSION   => 5,
                Characteristic::KNOWLEDGE    => 5,
                Characteristic::PERCEPTION   => 5,
                Characteristic::POWER        => 5,
                Characteristic::STRENGTH     => 5,
            ],
            []
        );
    }
}