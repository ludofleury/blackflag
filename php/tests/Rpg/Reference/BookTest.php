<?php
declare(strict_types=1);

namespace Tests\Rpg\Reference;

use DomainException;
use Rpg\Reference\Book;
use PHPUnit\Framework\TestCase;
use Rpg\Reference\Isbn;

class BookTest extends TestCase
{
    public function testHasIsbn(): void
    {
        $book = new Book('0-45122-5244', 1);
        $this->assertEquals(new Isbn('0-45122-5244'), $book->getIsbn());
    }

    public function testHasPageNumber(): void
    {
        $book = new Book('0-45122-5244', 2);
        $this->assertEquals(2, $book->getPage());
    }

    public function testSupportsPage0ForCoverInformation(): void
    {
        $book = new Book('0-45122-5244', 0);
        $this->assertEquals(0, $book->getPage());
    }

    public function testRejectsInvalidPage(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('Page number cannot be negative');
        new Book('0-45122-5244', -1);
    }
}
