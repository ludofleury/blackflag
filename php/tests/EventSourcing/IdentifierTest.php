<?php
declare(strict_types=1);

namespace Tests\EventSourcing;

use EventSourcing\Identifier;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class IdentifierTest extends TestCase
{
    public function testHasUuidValue(): void
    {
        $uuid = Uuid::uuid4();
        $id = new class($uuid) extends Identifier
        {
            /** @noinspection PhpHierarchyChecksInspection */
            public static function fromString(string $uuid): static
            {
                return new static(Uuid::fromString($uuid));
            }
        };
        $this->assertTrue($id->getValue()->equals($uuid));
    }

    public function testHasStringRepresentation(): void
    {
        $uuid = Uuid::uuid4();
        $id = new class($uuid) extends Identifier
        {
            /** @noinspection PhpHierarchyChecksInspection */
            public static function fromString(string $uuid): static
            {
                return new static(Uuid::fromString($uuid));
            }
        };
        $this->assertEquals($uuid->toString(), $id->toString());
        $this->assertEquals($uuid->__toString(), $id->__toString());
    }

    public function testNormalizesFromString(): void
    {
        $uuid = Uuid::uuid4();
        $id = new class($uuid) extends Identifier
        {
            /** @noinspection PhpHierarchyChecksInspection */
            public static function fromString(string $uuid): static
            {
                return new static(Uuid::fromString($uuid));
            }
        };

        /** @var class-string<Identifier> $class */
        $class = get_class($id);
        $id = $class::fromString($uuid->toString());

        $this->assertInstanceOf($class, $id);
        $this->assertEquals($uuid, $id->getValue());
    }
}