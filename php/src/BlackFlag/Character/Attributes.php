<?php

namespace BlackFlag\Character;

use BlackFlag\Character\Event\CharacterCreated;
use BlackFlag\Character\Exception\AttributesMissingException;
use BlackFlag\Character\Exception\AttributesTooLowException;
use EventSourcing\ChildEntity;

/**
 * Collection of primary statistics for Black Flag characters
 *
 * @see Attribute
 */
final class Attributes extends ChildEntity
{
    static private array $RULES = [
        'min' => 46 // minimum of the sum of all attributes at the creation
    ];

    /**
     * @var Attribute[]
     */
    private array $data;

    public function __construct(Character $character)
    {
        parent::__construct($character);
    }

    protected function applyCharacterCreated(CharacterCreated $event): void
    {
        $attributes = $event->getAttributes();
        $rules = Attribute::getSupportedAttributes();

        if (count($attributes) !== count($rules)) {
            $input = array_keys($attributes);
            $required = array_keys($rules);
            $missing = array_diff($required, $input);
            throw new AttributesMissingException(sprintf('Missing attributes: %s', implode(',', $missing)));
        }

        foreach ($attributes as $name => $value) {
            $this->data[$name] = new Attribute($name, $value);
        }

        if ($this->getTotal() < self::$RULES['min']) {
            throw new AttributesTooLowException(
                sprintf(
                    '%d attribute points minimum requirement: %d is too low',
                    self::$RULES['min'],
                    $this->getTotal()
                )
            );
        }
    }

    public function getTotal(): int
    {
        $total = 0;
        foreach ($this->data as $attribute) {
            $total += $attribute->getValue();
        }

        return $total;
    }
}