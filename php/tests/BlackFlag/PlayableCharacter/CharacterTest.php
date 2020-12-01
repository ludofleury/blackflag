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
use BlackFlag\PlayableCharacter\Event\CharacterCreated;
use BlackFlag\Skill\Combat;
use BlackFlag\Skill\Technical;
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
                Adaptability::name => 5,
                Charisma::name     => 5,
                Constitution::name => 5,
                Dexterity::name    => 5,
                Expression::name   => 5,
                Knowledge::name    => 5,
                Perception::name   => 5,
                Power::name        => 5,
                Strength::name     => 6,
            ],
            [
                ['name' => Combat::DODGING, 'level' => 1],
                ['name' => Combat::MELEE, 'special' => Combat::MELEE_AXE, 'level' => 2],
                ['name' => Technical::ART, 'special' => 'singing', 'level' => 3, 'pro' => true],
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
                        Adaptability::name => 5,
                        Charisma::name     => 5,
                        Constitution::name => 5,
                        Dexterity::name    => 5,
                        Expression::name   => 5,
                        Knowledge::name    => 5,
                        Perception::name   => 5,
                        Power::name        => 5,
                        Strength::name     => 6,
                    ],
                    [
                        ['name' => Combat::DODGING, 'level' => 1],
                        ['name' => Combat::MELEE, 'special' => Combat::MELEE_AXE, 'level' => 2],
                        ['name' => Technical::ART, 'special' => 'singing', 'level' => 3, 'pro' => true],
                    ]
                ),
            ],
            $john,
        );
    }
}