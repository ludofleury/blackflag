<?php
declare(strict_types=1);

namespace Tests\EventSourcing\Psalm;

use EventSourcing\EventSourced;
use EventSourcing\Psalm\EventSourcedPlugin;
use PhpParser\Node\Stmt\ClassLike;
use PHPUnit\Framework\TestCase;
use Psalm\Codebase;
use Psalm\FileSource;
use Psalm\Storage\ClassLikeStorage;

class EventSourcedPluginTest extends TestCase
{
    public function testSuppressesIssuesForEventSourcedClass(): void
    {
        $eventSourcedClass = get_class(new class() implements EventSourced{});

        $classLike = $this->createMock(ClassLike::class);
        $fileSource = $this->createMock(FileSource::class);
        $codebase = $this->createMock(Codebase::class);

        $classStorage = $this->createMock(ClassLikeStorage::class);
        $classStorage->user_defined = true;
        $classStorage->is_interface = false;
        $classStorage->name = $eventSourcedClass;
        $classStorage->suppressed_issues = [];

        EventSourcedPlugin::afterClassLikeVisit(
            $classLike,
            $classStorage,
            $fileSource,
            $codebase
        );

        $this->assertEquals(['PropertyNotSetInConstructor'], $classStorage->suppressed_issues);
    }

    public function testIgnoresNonEventSourcedClass(): void
    {
        $eventSourcedClass = get_class(new class(){});

        $classLike = $this->createMock(ClassLike::class);
        $fileSource = $this->createMock(FileSource::class);
        $codebase = $this->createMock(Codebase::class);

        $classStorage = $this->createMock(ClassLikeStorage::class);
        $classStorage->user_defined = true;
        $classStorage->is_interface = false;
        $classStorage->name = $eventSourcedClass;
        $classStorage->suppressed_issues = [];

        EventSourcedPlugin::afterClassLikeVisit(
            $classLike,
            $classStorage,
            $fileSource,
            $codebase
        );

        $this->assertEquals([], $classStorage->suppressed_issues);
    }
}
