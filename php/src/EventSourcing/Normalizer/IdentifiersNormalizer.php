<?php
declare(strict_types=1);

namespace EventSourcing\Normalizer;

use EventSourcing\Identifier;

trait IdentifiersNormalizer
{
    /**
     * @param class-string<Identifier> $class
     * @param array<string> $data
     * @return array<mixed>
     */
    public static function normalizeIdentifiers(string $class, array $data): array
    {
        $normalized = [];
        foreach ($data as $id) {
            $normalized[] = $class::fromString($id);
        }

        return $normalized;
    }

    /**
     * @return array<int, string>
     */
    public function denormalizeIdentifiers(Identifier ...$identifiers): array
    {
        $denormalized = [];
        foreach ($identifiers as $identifier) {
            $denormalized[] = $identifier->toString();
        }

        return $denormalized;
    }
}