<?php

namespace App\Documentation\Aspect\DTO;

use App\Documentation\Aspect\Builders\AspectPathBuilder;
use Illuminate\Support\Str;

final readonly class AspectPathDTO implements \Stringable
{
    public const LANG = '{lang}';
    public const PRODUCT = '{product}';
    public const VERSION = '{version}';
    public const PAGE = '{page}';

    /**
     * Может отсутствовать
     * Добавляется только для маппера расширений
     */
    public const EXTENSION = '{extension}';

    public function __construct(
        /**
         * @example local
         * @see \App\Page\Driver\Contracts\Driver
         */
        private string $driver,
        /**
         * @example /var/www/storage/app/release/
         */
        private string $root,
        /**
         * @example /{version}/{version}_{lang}/{product}/{page}
         * @example /{version}/{version}_{lang}/{product}/{page}.{extension}
         */
        private string $pattern,
    ) {
    }

    public function getDriver(): string
    {
        return $this->driver;
    }

    public function getRoot(): string
    {
        return $this->root;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public static function getSupportedPatterns(): array
    {
        return [
            self::LANG,
            self::PRODUCT,
            self::VERSION,
            self::PAGE,
            self::EXTENSION,
        ];
    }

    public function existsExtensionPattern(): bool
    {
        return Str::contains($this->pattern, self::EXTENSION);
    }

    public function __toString(): string
    {
        return sprintf(
            '%s:%s:%s',
            $this->driver,
            $this->root,
            $this->pattern,
        );
    }
}
