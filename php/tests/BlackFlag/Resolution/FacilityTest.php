<?php
declare(strict_types=1);

namespace Tests\BlackFlag\Resolution;

use BlackFlag\Resolution\Facility;
use PHPStan\Testing\TestCase;

class FacilityTest extends TestCase
{
    public function testHasAThreshold(): void
    {
        $facility = new Facility(5);
        $this->assertEquals(5, $facility->getThreshold());
    }

    public function testCanHaveNegativeThreshold(): void
    {
        $facility = new Facility(-5);
        $this->assertEquals(-5, $facility->getThreshold());
    }

    public function testCanHaveThresholdOver10(): void
    {
        $facility = new Facility(10);
        $this->assertEquals(10  , $facility->getThreshold());
    }
}