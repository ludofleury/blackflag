<?php
declare(strict_types=1);

namespace Tests\Rpg\Reference;

use DomainException;
use Rpg\Reference\Book;
use PHPUnit\Framework\TestCase;
use Rpg\Reference\Isbn;

/**
 * International Standard Book Number
 * @see https://en.wikipedia.org/wiki/International_Standard_Book_Number
 *
 * Most of the code in this class was sourced from the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the Symfony MIT LICENCE
 * @license https://github.com/symfony/symfony/blob/5.x/LICENSE
 */
class IsbnTest extends TestCase
{
    /**
     * @return array<array<int, string>>
     */
    public function provideValidIsbn(): array
    {
        return [
            '2723442284' => ['2723442284'],
            '2723442276' => ['2723442276'],
            '2723455041' => ['2723455041'],
            '2070546810' => ['2070546810'],
            '2711858839' => ['2711858839'],
            '2756406767' => ['2756406767'],
            '2870971648' => ['2870971648'],
            '226623854X' => ['226623854X'],
            '2851806424' => ['2851806424'],
            '0321812700' => ['0321812700'],
            '0-45122-5244' => ['0-45122-5244'],
            '0-4712-92311' => ['0-4712-92311'],
            '0-9752298-0-X' => ['0-9752298-0-X'],

            '978-2723442282' => ['978-2723442282'],
            '978-2723442275' => ['978-2723442275'],
            '978-2723455046' => ['978-2723455046'],
            '978-2070546817' => ['978-2070546817'],
            '978-2711858835' => ['978-2711858835'],
            '978-2756406763' => ['978-2756406763'],
            '978-2870971642' => ['978-2870971642'],
            '978-2266238540' => ['978-2266238540'],
            '978-2851806420' => ['978-2851806420'],
            '978-0321812704' => ['978-0321812704'],
            '978-0451225245' => ['978-0451225245'],
            '978-0471292319' => ['978-0471292319'],
        ];
    }

    /**
     * @return array<array<int, string>>
     */
    public function provideInvalidIsbn(): array
    {
        return [
            '27234422841' => ['27234422841'],
            '272344228' => ['272344228'],
            '0-4712-9231' => ['0-4712-9231'],
            '1234567890' => ['1234567890'],
            '0987656789' => ['0987656789'],
            '7-35622-5444' => ['7-35622-5444'],
            '0-4X19-92611' => ['0-4X19-92611'],
            '0_45122_5244' => ['0_45122_5244'],
            '2870#971#648' => ['2870#971#648'],
            '0-9752298-0-x' => ['0-9752298-0-x'],
            '1A34567890' => ['1A34567890'],
            '2070546810' => ['2'.\chr(1).'70546810'], // chr(1) evaluates to 0: 2070546810 is valid

            '978-27234422821' => ['978-27234422821'],
            '978-272344228' => ['978-272344228'],
            '978-2723442-82' => ['978-2723442-82'],
            '978-2723442281' => ['978-2723442281'],
            '978-0321513774' => ['978-0321513774'],
            '979-0431225385' => ['979-0431225385'],
            '980-0474292319' => ['980-0474292319'],
            '0-4X19-92619812' => ['0-4X19-92619812'],
            '978_2723442282' => ['978_2723442282'],
            '978#2723442282' => ['978#2723442282'],
            '978-272C442282' => ['978-272C442282'],
            '978-2070546817' => ['978-2'.\chr(1).'70546817'], // chr(1) evaluates to 0: 978-2070546817 is valid
        ];
    }

    /**
     * @dataProvider provideValidIsbn
     */
    public function testAcceptsValidIsbn(string $number): void
    {
        $isbn = new Isbn($number);
        $this->assertEquals($number, $isbn->getNumber());
    }

    /**
     * @dataProvider provideInvalidIsbn
     */
    public function testRejectsInvalidIsbn(string $number): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage(sprintf('Invalid ISBN number "%s"', $number));

        new Isbn($number);
    }
}
