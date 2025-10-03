<?php

namespace App\Config\DTO;

use App\Config\Enums\FeatureFlagEnum;
use App\Config\Enums\ModificationRow;
use Symfony\Component\Serializer\Annotation\SerializedName;

final readonly class ConfigDTO
{
    public function __construct(
        #[SerializedName('zip_folder')]
        public string $zipFolder,
        #[SerializedName('release_folder')]
        public string $releaseFolder,
        #[SerializedName('aspects_unsafe_folder')]
        public string $aspectsUnsafeFolder,
        #[SerializedName('search_engine')]
        public string $searchEngine,
        #[SerializedName('tooltip_timeout')]
        public string $tooltipTimeout,
        #[SerializedName('corrections_selectors')]
        public string $correctionsSelectors,
        #[SerializedName('corrections_count_for_premoderation')]
        public string $correctionsCountForPremoderation,
        #[SerializedName('corrections_per_page')]
        public string $correctionsPerPage,
        #[SerializedName('color_delete')]
        public string $colorDelete,
        #[SerializedName('color_add')]
        public string $colorAdd,
        #[SerializedName('scripts_body')]
        public string $scriptsBody,
        #[SerializedName('popup_with_rules_head')]
        public string $popupWithRulesHead,
        #[SerializedName('popup_with_rules_body')]
        public string $popupWithRulesBody,
        #[SerializedName('page_404_body')]
        public string $page404Body,
        #[SerializedName('watcher_directory_feature_flag')]
        public FeatureFlagEnum $watcherDirectoryFeatureFlag,
        #[SerializedName('product_indexer_feature_flag')]
        public FeatureFlagEnum $productIndexerFeatureFlag,
        #[SerializedName('product_database_saver_feature_flag')]
        public FeatureFlagEnum $productDatabaseSaverFeatureFlag,
        #[SerializedName('menu_feature_flag')]
        public FeatureFlagEnum $menuFeatureFlag,
        #[SerializedName('menu_generation_feature_flag')]
        public FeatureFlagEnum $menuGenerationFeatureFlag,
        #[SerializedName('editor_js_feature_flag')]
        public FeatureFlagEnum $editorJsFeatureFlag,
        #[SerializedName('template')]
        public string $template,
        /**
         * @example ["rus:matlab:R2018b"]
         * @var list<string>
         */
        #[SerializedName(ModificationRow::LIST_OF_ASPECTS_WORK_DIRECTLY)]
        public array $listOfAspectsWorkDirectly,
        /**
         * @example ["rus:matlab:R2018b"]
         * @var list<string>
         */
        #[SerializedName(ModificationRow::LIST_OF_WIKI_ASPECTS)]
        public array $listOfWikiAspects,
    ) {
    }
}
