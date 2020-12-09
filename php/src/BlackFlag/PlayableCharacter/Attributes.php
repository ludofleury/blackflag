<?php
declare(strict_types=1);

namespace BlackFlag\PlayableCharacter;

use BlackFlag\Attribute;
use BlackFlag\Attribute\Characteristic;
use BlackFlag\PlayableCharacter\Event\CharacterCreated;
use BlackFlag\PlayableCharacter\Event\CharacterImprovedAttribute;
use BlackFlag\PlayableCharacter\Exception\CharacteristicsMissingException;
use BlackFlag\PlayableCharacter\Exception\CharacteristicsTooLowException;
use BlackFlag\PlayableCharacter\Exception\UnknownCharacteristicsException;
use EventSourcing\ChildEntity;

final class Attributes extends ChildEntity
{
    /** @var array<string, Attribute> */
    private array $characteristics;

    /** @var array<string, Attribute> */
    private array $occupationValue;

    /** @var array<string, Attribute> */
    private array $commandValue;

    public function __construct(Character $character) {
        parent::__construct($character);
    }

    protected function applyCharacterCreated(CharacterCreated $event): void
    {
        $required = Characteristic::list();
        
        $characteristics = $event->getCharacteristics();
        $characteristicNames = array_keys($characteristics);

        $missingCharacteristics = array_diff($required, $characteristicNames);
        if (count($missingCharacteristics) !== 0) {
            throw new CharacteristicsMissingException(sprintf('Missing characteristic%s: %s', count($missingCharacteristics) > 1 ? 's' : '', implode(', ', $missingCharacteristics)));
        }
        
        $unknownCharacteristics = array_diff( $characteristicNames, $required);
        if (count($unknownCharacteristics) > 0) {
            throw new UnknownCharacteristicsException(sprintf('Unknown characteristic%s: %s', count($unknownCharacteristics) > 1 ? 's' : '', implode(', ', $unknownCharacteristics),));
        }

        $total = 0;
        foreach ($characteristics as $name => $value) {
            $total += $value;
            $this->characteristics[$name] = new Attribute(
                new Characteristic($name),
                $value
            );
        }

        if ($total < 46) {
            throw new CharacteristicsTooLowException(
                sprintf(
                    '%d/46 characteristic points assigned: missing %d point%s',
                    $total,
                    46-$total,
                    46-$total > 1 ? 's' : ''
                )
            );
        }
    }
}