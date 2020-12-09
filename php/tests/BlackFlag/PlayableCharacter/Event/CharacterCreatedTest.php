<?php
declare(strict_types=1);

namespace Tests\BlackFlag\PlayableCharacter\Event;

use BlackFlag\Attribute\Characteristic;
use BlackFlag\PlayableCharacter\Event\CharacterCreated;
use BlackFlag\Skill\Domain\Combat;
use BlackFlag\Skill\Domain\Technical;
use PHPUnit\Framework\TestCase;

class CharacterCreatedTest extends TestCase
{
    /** @return array<string, array<CharacterCreated>> */
    public function providesEvent(): array
    {
        return [
            'John Doe' => [new CharacterCreated(
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
                    ['name' => Combat::DODGING, 'level' => 1],
                    ['name' => Combat::MELEE, 'special' => Combat::MELEE_AXE, 'level' => 2],
                    ['name' => Technical::ART, 'special' => 'singing', 'level' => 3, 'pro' => true],
                ],
            )]
        ];
    }

    /** @dataProvider providesEvent */
    public function testHasFirstname(CharacterCreated $event): void
    {
        $this->assertEquals('John', $event->getFirstname());
    }

    /** @dataProvider providesEvent */
    public function testHasLastname(CharacterCreated $event): void
    {
        $this->assertEquals('Doe', $event->getLastname());
    }

    /** @dataProvider providesEvent */
    public function testHasNickname(CharacterCreated $event): void
    {
        $this->assertEquals('Black beard', $event->getNickname());
    }

    /** @dataProvider providesEvent */
    public function testHasAge(CharacterCreated $event): void
    {
        $this->assertEquals(35, $event->getAge());
    }

    /** @dataProvider providesEvent */
    public function testHasGender(CharacterCreated $event): void
    {
        $this->assertEquals(true, $event->getGender());
    }

    /** @dataProvider providesEvent */
    public function testHasAttribute(CharacterCreated $event): void
    {
        $this->assertEquals(
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
            $event->getCharacteristics()
        );
    }

    /** @dataProvider providesEvent */
    public function testHasSkills(CharacterCreated $event): void
    {
        $this->assertEquals(
            [
                ['name' => Combat::DODGING, 'level' => 1],
                ['name' => Combat::MELEE, 'special' => Combat::MELEE_AXE, 'level' => 2],
                ['name' => Technical::ART, 'special' => 'singing', 'level' => 3, 'pro' => true],
            ],
            $event->getSkills()
        );
    }

    /** @dataProvider providesEvent */
    public function testDenormalizesAsArray(CharacterCreated $event): void
    {
        $this->assertEquals(
            [
                'firstname' => 'John',
                'lastname' => 'Doe',
                'nickname' => 'Black beard',
                'age' => 35,
                'gender' => true,
                'characteristics' => [
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
                'skills' => [
                    ['name' => Combat::DODGING, 'level' => 1],
                    ['name' => Combat::MELEE, 'special' => Combat::MELEE_AXE, 'level' => 2],
                    ['name' => Technical::ART, 'special' => 'singing', 'level' => 3, 'pro' => true],
                ]
            ],
            $event->toArray(),
        );
    }

    /** @dataProvider providesEvent */
    public function testNormalizesFromArray(CharacterCreated $expected): void
    {
        $event = CharacterCreated::fromArray([
            'firstname' => 'John',
            'lastname' => 'Doe',
            'nickname' => 'Black beard',
            'age' => 35,
            'gender' => true,
            'characteristics' => [
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
            'skills' => [
                ['name' => Combat::DODGING, 'level' => 1],
                ['name' => Combat::MELEE, 'special' => Combat::MELEE_AXE, 'level' => 2],
                ['name' => Technical::ART, 'special' => 'singing', 'level' => 3, 'pro' => true],
            ]
        ]);

        $this->assertEquals($expected, $event);
    }
}