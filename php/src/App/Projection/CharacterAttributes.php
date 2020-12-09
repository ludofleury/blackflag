<?php
declare(strict_types=1);

namespace App\Projection;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable()
 */
class CharacterAttributes
{
    /**
     * @ORM\Column(type="smallint", name="adaptability", options={"unsigned":true, "default": 0})
     */
    public int $adaptability;

    /**
     * @ORM\Column(type="smallint", name="charisma", options={"unsigned":true, "default": 0})
     */
    public int $charisma;

    /**
     * @ORM\Column(type="smallint", name="constitution", options={"unsigned":true, "default": 0})
     */
    public int $constitution;

    /**
     * @ORM\Column(type="smallint", name="dexterity", options={"unsigned":true, "default": 0})
     */
    public int $dexterity;

    /**
     * @ORM\Column(type="smallint", name="expression", options={"unsigned":true, "default": 0})
     */
    public int $expression;

    /**
     * @ORM\Column(type="smallint", name="knowledge",options={"unsigned":true, "default": 0})
     */
    public int $knowledge;

    /**
     * @ORM\Column(type="smallint", name="perception", options={"unsigned":true, "default": 0})
     */
    public int $perception;

    /**
     * @ORM\Column(type="smallint", name="power", options={"unsigned":true, "default": 0})
     */
    public int $power;

    /**
     * @ORM\Column(type="smallint", name="strength", options={"unsigned":true, "default": 0})
     */
    public int $strength;

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
     * } $attributes
     */
    public function __construct(array $attributes)
    {
        foreach ($attributes as $name => $value) {
            if (property_exists($this, $name)) {
                $this->$name = $value;
            }
        }
    }
}