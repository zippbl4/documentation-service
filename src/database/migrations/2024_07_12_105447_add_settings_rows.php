<?php

use App\Config\Entities\Settings;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Settings::create([
            'name' => 'zip_folder',
            'description' => 'Папка для хранения архивов',
            'val' => '',
        ]);

        Settings::create([
            'name' => 'release_folder',
            'description' => 'Папка для хранения релизов',
            'val' => '',
        ]);

        Settings::create([
            'name' => 'search_engine',
            'description' => 'Поисковой движок',
            'val' => '',
        ]);

        Settings::create([
            'name' => 'tooltip_timeout',
            'description' => 'Время задержки появления всплывающего сообщения',
            'val' => '',
        ]);

        Settings::create([
            'name' => 'corrections_selectors',
            'description' => 'Селекторы редактируемых элементов',
            'val' => '',
        ]);

        Settings::create([
            'name' => 'corrections_count_for_premoderation',
            'description' => 'Колличество одобренных правок для автоматической премодерации. Пользователь, который внес более N одобренных администратором правок (настраивается) получает возможность вносить правки без премодерации (то есть правки пользователя сразу становятся доступны всем пользователям, а не только ему самому).',
            'val' => '',
        ]);

        Settings::create([
            'name' => 'corrections_per_page',
            'description' => 'Количество правок на страницу в списке правок в админке',
            'val' => '',
        ]);

        Settings::create([
            'name' => 'color_delete',
            'description' => 'Выделение удалённого текста',
            'val' => '',
        ]);

        Settings::create([
            'name' => 'color_add',
            'description' => 'Выделение добавленного текста',
            'val' => '',
        ]);

        Settings::create([
            'name' => 'scripts_body',
            'description' => 'Html-код в body. Можно вставлять любой html-код, но осторожно.',
            'val' => '',
        ]);

        Settings::create([
            'name' => 'popup_with_rules_head',
            'description' => 'Pop-up с правилами добавления правок (заголовок)',
            'val' => '',
        ]);

        Settings::create([
            'name' => 'popup_with_rules_body',
            'description' => 'Pop-up с правилами добавления правок (контент). Можно вставлять любой html-код, но осторожно.',
            'val' => '',
        ]);

        Settings::create([
            'name' => 'page_404_body',
            'description' => 'Страница 404 (контент). Можно вставлять любой html-код, но осторожно.',
            'val' => '',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
