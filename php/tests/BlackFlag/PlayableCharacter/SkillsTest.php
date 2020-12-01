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
use BlackFlag\PlayableCharacter\Exception\SkillNotLearned;
use BlackFlag\Skill;
use BlackFlag\Skill\Combat;
use BlackFlag\Skill\Technical;
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