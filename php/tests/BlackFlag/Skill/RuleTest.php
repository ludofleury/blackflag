<?php
declare(strict_types=1);

namespace Tests\BlackFlag\Skill;

use LogicException;
use BlackFlag\Skill\Rule;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class RuleTest extends TestCase
{
    public function testRejectsInvalidSkillType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid skill type "random"');

        new Rule('test', 'random', true);
    }

    public function testRejectsSpecializedAndProfessionalSkill(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Skill cannot be specialized and professional');

        new Rule('test', 'specialized', true);
    }

    public function testHasName(): void
    {
        $rule = new Rule('test', 'specialized', false);
        $this->assertEquals('test', $rule->getName());
    }

    public function testHasType(): void
    {
        $rule = new Rule('test', 'simple', true);
        $this->assertTrue($rule->isSimple());
        $this->assertFalse($rule->isSpecialized());
        $this->assertFalse($rule->isSpecialization());

        $rule = new Rule('test', 'specialized', false);
        $this->assertFalse($rule->isSimple());
        $this->assertTrue($rule->isSpecialized());
        $this->assertFalse($rule->isSpecialization());

        $rule = new Rule('test', 'specialization', true);
        $this->assertFalse($rule->isSimple());
        $this->assertFalse($rule->isSpecialized());
        $this->assertTrue($rule->isSpecialization());
    }

    public function testHasProfessionalInfo(): void
    {
        $rule = new Rule('test', 'specialization', true);
        $this->assertTrue($rule->isProfessional());

        $rule = new Rule('test', 'specialization', false);
        $this->assertFalse($rule->isProfessional());
    }
}