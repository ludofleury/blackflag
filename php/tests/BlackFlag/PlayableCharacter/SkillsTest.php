<?php
declare(strict_types=1);

namespace Tests\BlackFlag\PlayableCharacter;

use BlackFlag\Attribute\Characteristic;
use BlackFlag\PlayableCharacter\Character;
use BlackFlag\PlayableCharacter\Exception\SkillNotLearned;
use BlackFlag\Skill;
use BlackFlag\Skill\Domain\Combat;
use BlackFlag\Skill\Domain\Technical;
use PHPUnit\Framework\TestCase;

class SkillsTest extends TestCase
{
    /** @return array<string, array<Character>> */
    public function providesCharacter(): array
    {
        return [
            'John Doe' =>  [
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
                    ],
                    [
                        ['domain' => Combat::DODGING, 'level' => 1],
                        ['domain' => Combat::MELEE, 'special' => Combat::MELEE_AXE, 'level' => 2],
                        ['domain' => Technical::ART, 'special' => 'singing', 'level' => 3, 'pro' => true],
                    ],
                )
            ]
        ];
    }

    /** @dataProvider  providesCharacter */
    public function testAssignsSkillsFromCreation(Character $character): void
    {
        $skill = $character->getSkill('dodging');
        $this->assertInstanceOf( Skill::class, $skill);
        $this->assertEquals( 'dodging', $skill->getName());
        $this->assertFalse(  $skill->isProfessional());
        $this->assertFalse(  $skill->isSpecialized());
        $this->assertEquals( 1, $skill->getLevel());

        $skill = $character->getSkill('axe');
        $this->assertInstanceOf( Skill::class, $skill);
        $this->assertEquals( 'melee weapon', $skill->getName());
        $this->assertFalse(  $skill->isProfessional());
        $this->assertTrue(  $skill->isSpecialized());
        $this->assertEquals( 'axe', $skill->getSpecialization());
        $this->assertEquals( 2, $skill->getLevel());

        $skill = $character->getSkill('singing');
        $this->assertInstanceOf( Skill::class, $skill);
        $this->assertEquals( 'art', $skill->getName());
        $this->assertTrue(  $skill->isProfessional());
        $this->assertTrue(  $skill->isSpecialized());
        $this->assertEquals( 'singing', $skill->getSpecialization());
        $this->assertEquals( 3, $skill->getLevel());
    }

    /** @dataProvider  providesCharacter */
    public function testInformsWhenASkillIsNotLearned(Character $character): void
    {
        $this->expectException(SkillNotLearned::class);
        $character->getSkill('unknown');
    }
}