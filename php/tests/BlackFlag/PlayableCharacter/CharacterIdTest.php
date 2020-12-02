<?php
declare(strict_types=1);

namespace Tests\BlackFlag\PlayableCharacter;

use BlackFlag\PlayableCharacter\CharacterId;
use EventSourcing\Identifier;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CharacterIdTest extends TestCase
{
    public function testIsIdentifier(): void
    {
        $uuid = Uuid::uuid4();
        $id = new CharacterId($uuid);
        $this->assertInstanceOf(Identifier::class, $id);
    }

    public function testComparesToOthers(): void
    {
        $uuid = Uuid::uuid4();
        $id = new CharacterId($uuid);
        $other = new CharacterId($uuid);

        $this->assertTrue($id->equals($other));
    }

    public function testNormalizesFromString(): void
    {
        $uuid = Uuid::uuid4();
        $id = CharacterId::fromString($uuid->toString());

        $this->assertInstanceOf(CharacterId::class, $id);
        $this->assertEquals($uuid, $id->getValue());
    }
}