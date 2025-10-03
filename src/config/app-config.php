<?php

use App\Config\Enums\ModificationRow;

return [
    // основное
    'zip_folder' => env('ZIP_FOLDER', fn (...$args) => throw new InvalidArgumentException()),
    'release_folder' => env('RELEASE_FOLDER', fn (...$args) => throw new InvalidArgumentException()),
    'aspects_unsafe_folder' => env('ASPECTS_UNSAFE_FOLDER', fn (...$args) => throw new InvalidArgumentException()),
    'search_engine' => env('SEARCH_ENGINE'),
    ModificationRow::LIST_OF_ASPECTS_WORK_DIRECTLY => [],
    ModificationRow::LIST_OF_WIKI_ASPECTS => [],

    // хз
    'tooltip_timeout' => '',
    'corrections_selectors' => '',
    'corrections_count_for_premoderation' => '',
    'corrections_per_page' => '',
    'color_delete' => '',
    'color_add' => '',
    'scripts_body' => '',
    'popup_with_rules_head' => '',
    'popup_with_rules_body' => '',
    'page_404_body' => '',

    // фичи
    'watcher_directory_feature_flag' => 0,
    'product_indexer_feature_flag' => 0,
    'product_database_saver_feature_flag' => 0,
    'menu_feature_flag' => 1,
    'menu_generation_feature_flag' => 0,
    'editor_js_feature_flag' => 0,

    // дизайн
    'template' => 'confluence',

];
