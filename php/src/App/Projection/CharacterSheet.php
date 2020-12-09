<?php
declare(strict_types=1);

namespace App\Projection;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity()
 * @ORM\ChangeTrackingPolicy("DEFERRED_EXPLICIT")
 * @ORM\Table(schema="projection", name="character_sheets")
 */
class CharacterSheet
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="uuid")
     */
    public UuidInterface $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    public string $firstname;

    /**
     * @ORM\Column(type="string", length=100)
     */
    public string $lastname;

    /**
     * @ORM\Column(type="string", length=100)
     */
    public string $nickname;

    /**
     * @ORM\Column(type="smallint", options={"unsigned":true})
     */
    public int $age;

    /**
     * @ORM\Column(type="smallint", name="experience", options={"unsigned":true, "default": 0})
     */
    public int $experience;

    /**
     * @ORM\Embedded(columnPrefix="attribute", class="CharacterAttributes")
     */
    public CharacterAttributes $attributes;

    /**
     * @ORM\Column(type="json", nullable=true, options={"jsonb": true})
     * @var array<array{name: string, level: int, special?: string, pro?: bool}>
     */
    public array $skills;

    /**
     * @param array{
     *  adaptability: int,
     *  charisma: int,
     *  constitution: int,
     *  dexterity: int,
     *  expression: int,
     *  knowledge: int,
     *  perception: int,
     *  power: int,
     *  strength: int
     *  } $attributes
     * @param array<array{name: string, level: int, special?: string, pro?: bool}> $skills
     */
    public function __construct(
        UuidInterface $id,
        string $firstname,
        string $lastname,
        string $nickname,
        int $age,
        int $experience,
        array $attributes,
        array $skills
    ) {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->nickname = $nickname;
        $this->age = $age;
        $this->experience = $experience;
        $this->attributes = new CharacterAttributes($attributes);
        $this->skills = $skills;
    }
}