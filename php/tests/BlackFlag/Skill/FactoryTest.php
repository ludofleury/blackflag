<?php
declare(strict_types=1);

namespace Tests\BlackFlag\Skill;

use BlackFlag\Skill;
use BlackFlag\Skill\Factory;
use LogicException;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function testInstantiateSkillWithDefaultRules(): void
    {
        /** @var Factory|object $behavior */
        $behavior = $this->getObjectForTrait(Factory::class);

        $skill = $behavior::BALLISTICS(1); // @phpstan-ignore-line
        $this->assertInstanceOf(Skill::class, $skill);
        $this->assertEquals('ballistics', $skill->getName());
        $this->assertEquals(1, $skill->getLevel());
        $this->assertTrue($skill->isProfessional());
    }

    public function testInstantiateSpecializedSkill(): void
    {
        /** @var Factory|object $behavior */
        $behavior = $this->getObjectForTrait(Factory::class);

        $skill = $behavior::EXPERTISE(Skill\Knowledge::EXPERTISE_LAW, 1); // @phpstan-ignore-line
        $this->assertInstanceOf(Skill::class, $skill);
        $this->assertEquals('specialized knowledge', $skill->getName());
        $this->assertEquals('law', $skill->getSpecialization());
        $this->assertEquals(1, $skill->getLevel());
        $this->assertTrue($skill->isProfessional());
    }

    public function testCannotInstantiateSpecializationDirectly(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Unable to create skill directly from specialization');

        /** @var Factory|object $behavior */
        $behavior = $this->getObjectForTrait(Factory::class);

        $behavior::EXPERTISE_LAW(1); // @phpstan-ignore-line
    }

    public function testCannotInstantiateUnknownSkill(): void
    {
        $this->expectException(Skill\Exception\SkillException::class);
        $this->expectExceptionMessage('Unknown skill ID "BlackFlag\\Skill\\Registry::RANDOM"');

        /** @var Factory|object $behavior */
        $behavior = $this->getObjectForTrait(Factory::class);
        $behavior::RANDOM(1);  // @phpstan-ignore-line

    }
}