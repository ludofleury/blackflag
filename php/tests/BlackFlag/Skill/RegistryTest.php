<?php
declare(strict_types=1);

namespace Tests\BlackFlag\Skill;

use BlackFlag\Skill\Exception\SkillException;
use BlackFlag\Skill\Registry;
use BlackFlag\Skill\Rule;
use PHPStan\Testing\TestCase;

class RegistryTest extends TestCase
{
    public function testProvidesOfficialSkillDefaultRules(): void
    {
        $rule = Registry::getDefaultSkillRule('ballistics');
        $this->assertInstanceOf(Rule::class, $rule);
    }

    public function testDoesNotSupportUnofficialRules(): void
    {
        $this->expectException(SkillException::class);
        $this->expectExceptionMessage('Unknown skill "random"');
        Registry::getDefaultSkillRule('random');
    }
}