<?php
declare(strict_types=1);

namespace Tests\BlackFlag\PlayableCharacter;

use BlackFlag\Attribute\Characteristic;
use BlackFlag\PlayableCharacter\Character;
use BlackFlag\PlayableCharacter\Event\CharacterCreated;
use BlackFlag\Skill\Domain\Combat;
use BlackFlag\Skill\Domain\Technical;
use EventSourcing\Testing\EsTestCase;

final class CharacterTest extends EsTestCase
{
    public function testCanBeCreated(): void
    {
        $john = Character::create(
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
            ],
            [
                ['domain' => Combat::DODGING, 'level' => 1],
                ['domain' => Combat::MELEE, 'special' => Combat::MELEE_AXE, 'level' => 2],
                ['domain' => Technical::ART, 'special' => 'singing', 'level' => 3, 'pro' => true],
            ]
        );

        $this->assertUncommittedEvents(
            [
                new CharacterCreated(
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
                    ],
                    [
                        ['domain' => Combat::DODGING, 'level' => 1],
                        ['domain' => Combat::MELEE, 'special' => Combat::MELEE_AXE, 'level' => 2],
                        ['domain' => Technical::ART, 'special' => 'singing', 'level' => 3, 'pro' => true],
                    ]
                ),
            ],
            $john,
        );
    }
}