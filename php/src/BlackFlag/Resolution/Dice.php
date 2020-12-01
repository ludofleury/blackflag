<?php
declare(strict_types=1);

namespace BlackFlag\Resolution;

use BlackFlag\Resolution\Dice\Factory;
use DomainException;
use Rpg\Dice\AnyDice;
use Rpg\Dice\Generator\PhpCSPRNGenerator;
use Rpg\Dice\RandomGenerator;

final class Dice extends AnyDice
{
    use Factory;

    public const D10 = 10;
    public const D12 = 12;
    public const D20 = 20;
    public const D100 = 100;

    private const SUPPORTED = [self::D10, self::D12, self::D20, self::D100];

    public function __construct(int $sides, RandomGenerator $generator = null)
    {
        if (!in_array($sides, self::SUPPORTED)) {
            throw new DomainException(sprintf('Unsupported action dice "D%d"', $sides));
        }

        parent::__construct(
            $sides,
            $generator
        );
    }

    public function isCriticalFailure(): bool
    {
        return $this->getResult() >= 10;
    }

    public function isCriticalSuccess(): bool
    {
        return $this->getResult() == 1;
    }

    public function isRegularSuccessFor(Facility $facility): bool
    {
        return
            $this->getResult() < 10 &&
            !$this->isCriticalSuccess() &&
            $this->getResult() <= $facility->getThreshold();
    }
}