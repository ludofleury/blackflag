<?php
declare(strict_types=1);

namespace BlackFlag\Skill;

use BlackFlag\Skill\Exception\SkillException;

use Rpg\Reference as RPG;
use RuntimeException;

/**
 * Official skill domain rule index
 */
#[RPG\Book(ISBN: '978-2-36328-252-1', page: 14)]
final class Registry
{
    public const SIMPLE = 0b0001;
    public const MACRO = 0b0010;
    public const SPECIAL = 0b0100;
    public const PRO = 0b1000;

    /** @var array<string, int> */
    private static array $rules = [];
    private static bool $loaded = false;

    /**
     * @return bool Whether or not the name match any kind of skill domain
     */
    public static function has(string $name): bool
    {
        if (!self::$loaded) {
            self::load();
        }

        return isset(self::$rules[$name]);
    }

    /**
     * @return bool Whether or not the name match a "main" skill (simple or specialized, but not specialization)
     */
    public static function hasDomain(string $name): bool
    {
        return self::has($name) && !(self::get($name) & self::SPECIAL);
    }

    public static function getDefaultRule(string $name): Rule
    {
        if (!self::has($name)) {
            throw new SkillException(sprintf('Unknown skill "%s"', $name));
        }

        $rule = self::get($name);
        $type = Rule::SIMPLE;

        if ($rule & self::MACRO) {
            $type = Rule::SPECIALIZED;
        } elseif ($rule & self::SPECIAL)  {
            $type = Rule::SPECIALIZATION;
        }

        return new Rule(
            $name,
            $type,
            $rule & self::PRO ? true : false,
        );
    }

    private static function get(string $name): int
    {
        if (!self::$loaded) {
            self::load();
        }

        return self::$rules[$name];
    }

    private static function load(): void
    {
        $rootDir = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..';
        $file = $rootDir . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'official-skills.php';

        if (!file_exists($file)) {
            throw new RuntimeException('Unable to load official skill rules'); // @codeCoverageIgnore
        }

        self::$rules = require $file;
        self::$loaded = true;
    }
}