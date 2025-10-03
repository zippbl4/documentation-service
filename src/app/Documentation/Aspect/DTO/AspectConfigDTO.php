<?php

namespace App\Documentation\Aspect\DTO;

use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @TODO подумать над названиями
 */
final readonly class AspectConfigDTO
{
    public const IS_WORK_DIRECTLY = 'is_work_directly';
    public const IS_WIKI_ASPECT = 'is_wiki_aspect';
    public const FINDER_PRODUCT_PAGE_EXTENSION = 'finder_product_page_extension';
    public const FINDER_PRODUCT_LANG = 'finder_product_lang';

    public function __construct(
        #[SerializedName(self::FINDER_PRODUCT_PAGE_EXTENSION)]
        public ?string $finderProductPageExtension,
        #[SerializedName(self::FINDER_PRODUCT_LANG)]
        public ?string $finderProductLang,
        #[SerializedName(self::IS_WORK_DIRECTLY)]
        public ?bool $isWorkDirectly,
        #[SerializedName(self::IS_WIKI_ASPECT)]
        public ?bool $isWikiAspect,
    ) {
    }

    public static function getSupportedSettings(): array
    {
        return [
            self::IS_WORK_DIRECTLY => 'Поиск без использования регулярного выражения',
            self::IS_WIKI_ASPECT => 'Вики аспект',
            self::FINDER_PRODUCT_PAGE_EXTENSION => 'Расширения страниц которые должны быть обработаны (прим.: *.html)',
            self::FINDER_PRODUCT_LANG => 'Язык продукта который должен быть обработан (прим.: rus)',
        ];
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
