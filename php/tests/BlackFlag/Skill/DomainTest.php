<?php
declare(strict_types=1);

namespace Tests\BlackFlag\Skill;

use BlackFlag\Skill\Domain;
use BlackFlag\Skill\Domain\Knowledge;
use DomainException;
use LogicException;
use PHPUnit\Framework\TestCase;

class DomainTest extends TestCase
{
    public function testRejectsUnknownDomain(): void
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

    public function testSupportsCustomSpecialization(): void
    {
        $domain = new Domain(Knowledge::EXPERTISE, 'custom specialization');
        $this->assertTrue($domain->isSpecialized());
    }

    public function testRejectsSpecializationAsDomain(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Unknown skill domain "law"');

        new Domain(Knowledge::EXPERTISE_LAW);
    }
}