<?php
declare(strict_types=1);

namespace Tests\BlackFlag\Skill;

use BlackFlag\Skill\Domain;
use BlackFlag\Skill\Knowledge;
use DomainException;
use LogicException;
use PHPUnit\Framework\TestCase;

class DomainTest extends TestCase
{
    public function testRejectsUnknownSkill(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Unknown skill domain "random"');

        new Domain('random');
    }

    public function testCanBeSpecialized(): void
    {
        $domain = new Domain(Knowledge::BALLISTICS);
        $this->assertFalse($domain->isSpecialized());

        $domain = new Domain(Knowledge::EXPERTISE, Knowledge::EXPERTISE_LAW);
        $this->assertTrue($domain->isSpecialized());
    }

    public function testRejectsDirectSpecialization(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('"law" is a specialization, instantiate with BlackFlag\\Skill("[main skill]", "law", ...) instead');

        new Domain(Knowledge::EXPERTISE_LAW);
    }
}