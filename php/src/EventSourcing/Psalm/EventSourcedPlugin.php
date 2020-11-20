<?php
declare(strict_types=1);

namespace EventSourcing\Psalm;

use PhpParser\Node\Stmt\ClassLike;
use Psalm\Codebase;
use Psalm\FileSource;
use Psalm\Plugin\Hook\AfterClassLikeVisitInterface;
use Psalm\Storage\ClassLikeStorage;
use EventSourcing\EventSourced as EventSourcedInterface;
use ReflectionClass;

/**
 * Suppress PropertyNotSetInConstructor issues dynamically based for event sourced entities
 *
 * The implementation is strongly opinionated and disable __constructor initialization
 * Psalm cannot infer the creation event
 *
 * @see AfterClassLikeVisitInterface "Due to caching the AST is crawled the first time Psalm sees the file"
 * @see https://github.com/vimeo/psalm/issues/4684
 */
class EventSourcedPlugin implements AfterClassLikeVisitInterface
{
    public static function afterClassLikeVisit(
        ClassLike $stmt,
        ClassLikeStorage $storage,
        FileSource $statements_source,
        Codebase $codebase,
        array &$file_replacements = []
    )
    {
        if ($storage->user_defined && !$storage->is_interface) {

            /** @var class-string $visitedClassName */
            $visitedClassName = $storage->name;
            if ((new ReflectionClass($visitedClassName))->implementsInterface(EventSourcedInterface::class)) {
                $storage->suppressed_issues[] = 'PropertyNotSetInConstructor';
            }
        }
    }
}