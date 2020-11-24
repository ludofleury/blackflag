<?php
declare(strict_types=1);

namespace Tests\Rpg\Reference;

use DomainException;
use Rpg\Reference\Book;
use PHPUnit\Framework\TestCase;
use Rpg\Reference\Term;

class I18nTest extends TestCase
{
    public function testSupportsOnlyFrench(): void
    {
        $this->expectException(DomainException::class);
        new Term('en', 'test');
    }

    public function testHasALanguage(): void
    {
        $term = new Term('fr', 'test');
        $this->assertEquals('fr', $term->getLang());
    }

    public function testHasTranslatedText(): void
    {
        $term = new Term('fr', 'test');
        $this->assertEquals('test', $term->getText());
    }
}
