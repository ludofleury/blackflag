<?php

namespace Tests\BlackFlag\Character;

use BlackFlag\Character\Character;
use BlackFlag\Character\Event\CharacterCreated;
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
                'adaptability' => 5,
                'charisma'     => 5,
                'constitution' => 5,
                'dexterity'    => 5,
                'expression'   => 5,
                'knowledge'    => 5,
                'perception'   => 5,
                'power'        => 5,
                'strength'     => 6,
            ]
        );

        $this->assertHistory(
            $john,
            [
                new CharacterCreated(
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
                        'strength'     => 6,
                    ]
                ),
            ],
        );
    }
}