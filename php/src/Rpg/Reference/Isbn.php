<?php
declare(strict_types=1);

namespace Rpg\Reference;

use DomainException;

/**
 * International Standard Book Number
 * @see https://en.wikipedia.org/wiki/International_Standard_Book_Number
 *
 * Most of the code in this class was sourced from the Symfony package.
 * (c) Fabien Potencier <fabien@symfony.com>
 * For the full copyright and license information, please view the Symfony MIT LICENCE
 * @license https://github.com/symfony/symfony/blob/5.x/LICENSE
 */
class Isbn
{
    private string $number;

    public function __construct(string $number)
    {
        $canonical = str_replace('-', '', $number);
        if (!$this->validateIsbn10($canonical) && !$this->validateIsbn13($canonical)) {
            throw new DomainException(sprintf('Invalid ISBN number "%s"', $number));
        }
        $this->number = $number;
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    private function validateIsbn10(string $isbn): bool
    {
        $checkSum = 0;

        for ($i = 0; $i < 10; ++$i) {
            if (!isset($isbn[$i])) {
                return false;
            }

            if ('X' === $isbn[$i]) {
                $digit = 10;
            } elseif (ctype_digit($isbn[$i])) {
                $digit = (int) $isbn[$i];
            } else {
                return false;
            }

            $checkSum += $digit * (10 - $i);
        }

        if (isset($isbn[$i])) {
            return false;
        }

        return 0 === $checkSum % 11;
    }

    private function validateIsbn13(string $isbn): bool
    {
        if (!ctype_digit($isbn)) {
            return false;
        }

        $length = \strlen($isbn);
        if ($length !== 13) {
            return false;
        }

        $checkSum = 0;

        for ($i = 0; $i < 13; $i += 2) {
            $checkSum += (int) $isbn[$i];
        }

        for ($i = 1; $i < 12; $i += 2) {
            $checkSum += (int) ($isbn[$i]) * 3;
        }

        return 0 === $checkSum % 10;
    }
}