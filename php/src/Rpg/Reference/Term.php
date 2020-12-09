<?php
declare(strict_types=1);

namespace Rpg\Reference;

use Attribute;
use DomainException;

/**
 * I18n concern for role-playing game term / terminology
 * Reference the exact term in a specific language to avoid ambiguity
 *
 * example: Some rpg book are not translated yet terms are implemented in english
 */
#[Attribute(Attribute::TARGET_ALL | Attribute::IS_REPEATABLE)]
class Term
{
    /**
     * @var string ISO 639-1 (2-letters macrolanguage)
     */
    private string $lang;

    /**
     * @var string Original text
     */
    private string $text;

    public function __construct(string $lang, string $text)
    {
        $lang = strtolower($lang);

        if ($lang !== 'fr') {
            throw new DomainException('Only "fr" language is supported');
        }

        $this->lang = $lang;
        $this->text = $text;
    }

    public function getLang(): string
    {
        return $this->lang;
    }

    public function getText(): string
    {
        return $this->text;
    }
}