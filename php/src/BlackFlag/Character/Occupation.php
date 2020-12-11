<?php
declare(strict_types=1);

namespace BlackFlag\Attribute\Special;

use BlackFlag\Attribute\Characteristic;
use DomainException;
use Rpg\Reference as RPG;

#[RPG\Book(ISBN: '978-2-36328-252-1', page: 13)]
#[RPG\Term(lang: 'fr', text: 'métier')]
class Occupation
{
    private string $name;

    /** @var array<Characteristic>  */
    private array $relatedCharacteristics;

    public function __construct(string $name, Characteristic $characteristic1, Characteristic $characteristic2)
    {
        if ($characteristic1->getName() === $characteristic2->getName()) {
            throw new DomainException(
                sprintf(
                    'Occupation "%s" cannot relies twice on the same characteristic "%s"',
                    $name,
                    $characteristic1->getName()
                )
            );
        }

        $this->name = $name;
        $this->relatedCharacteristics[$characteristic1->getName()] = true;
        $this->relatedCharacteristics[$characteristic2->getName()] = true;
    }

    public function getName(): string
    {
        return $this->name;
    }
}