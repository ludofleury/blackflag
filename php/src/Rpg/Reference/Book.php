<?php
declare(strict_types=1);

namespace Rpg\Reference;

use Attribute;
use DomainException;

/**
 * Reference the source role-playing game book where a domain rule is specified
 */
#[Attribute(Attribute::TARGET_ALL)]
class Book
{
    private Isbn $isbn;

    private int $page;

    public function __construct(string $ISBN, int $page)
    {
        $this->isbn = new Isbn($ISBN);

        if ($page < 0) {
            throw new DomainException('Page number cannot be negative');
        }
        $this->page = $page;
    }

    public function getIsbn(): Isbn
    {
        return $this->isbn;
    }

    public function getPage(): int
    {
        return $this->page;
    }
}