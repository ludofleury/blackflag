<?php
declare(strict_types=1);

namespace BlackFlag\PlayableCharacter;

use BlackFlag\Attribute\Primary;
use BlackFlag\Attribute\Primary\Adaptability;
use BlackFlag\Attribute\Primary\Charisma;
use BlackFlag\Attribute\Primary\Constitution;
use BlackFlag\Attribute\Primary\Dexterity;
use BlackFlag\Attribute\Primary\Expression;
use BlackFlag\Attribute\Primary\Knowledge;
use BlackFlag\Attribute\Primary\Perception;
use BlackFlag\Attribute\Primary\Power;
use BlackFlag\Attribute\Primary\Strength;
use BlackFlag\PlayableCharacter\Event\CharacterCreated;
use BlackFlag\PlayableCharacter\Event\CharacterImprovedAttribute;
use BlackFlag\PlayableCharacter\Exception\AttributesMissingException;
use BlackFlag\PlayableCharacter\Exception\AttributesTooLowException;
use BlackFlag\PlayableCharacter\Exception\UnknownAttributesException;
use EventSourcing\ChildEntity;

final class PrimaryAttributes extends ChildEntity
{
    /**
     * @var array<string, class-string>
     */
    private const map = [
        Adaptability::name => Adaptability::class,
        Charisma::name => Charisma::class,
        Constitution::name => Constitution::class,
        Dexterity::name => Dexterity::class,
        Expression::name => Expression::class,
        Knowledge::name => Knowledge::class,
        Perception::name => Perception::class,
        Power::name => Power::class,
        Strength::name => Strength::class,
    ];

    /**
     * @var array<string, Primary>
     */
    private array $attributes;

    public function __construct(Character $character) {
        parent::__construct($character);
    }

    protected function applyCharacterCreated(CharacterCreated $event): void
    {
        $attributes = $event->getAttributes();

        $missingAttributes = array_diff(array_keys(self::map), array_keys($attributes));
        if (count($missingAttributes) !== 0) {
            throw new AttributesMissingException(sprintf('Missing attribute%s: %s', count($missingAttributes) > 1 ? 's' : '', implode(', ', $missingAttributes)));
        }

        $unknownAttributes = array_diff( array_keys($attributes), array_keys(self::map));
        if (count($unknownAttributes) > 0) {
            throw new UnknownAttributesException(sprintf('Unknown attribute%s: %s', count($unknownAttributes) > 1 ? 's' : '', implode(', ', $unknownAttributes),));
        }

        $total = 0;
        foreach ($attributes as $name => $value) {
            $total += $value;
            $this->attributes[$name] = new (self::map[$name])($value);
        }

        if ($total < 46) {
            throw new AttributesTooLowException(
                sprintf(
                    '%d/46 primary attributes points assigned: missing %d point',
                    $total,
                    46-$total
                )
            );
        }
    }

    protected function applyCharacterImprovedAttribute(CharacterImprovedAttribute $event): void
    {
        $this->attributes[$event->getName()] = $this->attributes[$event->getName()]->add($event->getProgress());
    }

}