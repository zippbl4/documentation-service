<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $path = \App\Documentation\Aspect\Entities\Path::create([
            'name' => 'Внутренняя документация 2.0',
            'driver' => 'local',
            'root' => '/var/www/storage/app/docs',
            'pattern' => '/{product}/{version}/{lang}/{page}.{extension}',
            'nginx_conf_template' => '',
        ]);

        $aspect = \App\Documentation\Aspect\Entities\Aspect::create([
            'path_id' => $path->id,
            'status' => 1,
            'name' => 'Внутренняя документация 2.0',
            'lang' => 'ru',
            'version' => '2.0',
            'product' => 'howto',
        ]);

        \App\Documentation\Aspect\Entities\Mapper::create([
            'aspect_id' => $aspect->id,
            'status' => 1,
            'name' => 'Расширение страницы',
            'pattern' => \App\Documentation\Aspect\DTO\AspectPathDTO::EXTENSION,
            'map_from' => 'html',
            'map_to' => 'md',
        ]);

        \App\Documentation\Aspect\Entities\Mapper::create([
            'aspect_id' => $aspect->id,
            'status' => 1,
            'name' => 'Расширение страницы',
            'pattern' => \App\Documentation\Aspect\DTO\AspectPathDTO::EXTENSION,
            'map_from' => '',
            'map_to' => 'md',
        ]);

        \App\Documentation\Aspect\Entities\Mapper::create([
            'aspect_id' => $aspect->id,
            'status' => 1,
            'name' => 'Страница',
            'pattern' => \App\Documentation\Aspect\DTO\AspectPathDTO::PAGE,
            'map_from' => '',
            'map_to' => 'readme',
        ]);

        \App\Documentation\Aspect\Entities\Decorator::create([
            'aspect_id' => $aspect->id,
            'name' => 'MarkdownToHtmlDecorator',
        ]);

        \App\Documentation\Aspect\Entities\Decorator::create([
            'aspect_id' => $aspect->id,
            'name' => 'CustomLayoutDecorator',
        ]);

        \App\Documentation\AspectPlugin\Product\ProductEntity::create([
            'aspect_id' => $aspect->id,
            'lang' => 'ru',
            'version' => '2.0',
            'product' => 'howto',
            'archive_hash' => '',
            'job_uuid' => '',
            'root_folder' => '/var/www/storage/app/docs',
            'root_path' => 'howto',
        ]);

        $parentMenu = \App\Menu\Entities\Menu::create([
            'filter' => ["lang" => "ru", "product" => "howto", "version" => "1.0"],
            'part' => 'howto',
            'title' => 'Начать работу',
            'href' => 'howto',
            'route' => [
                "name" => "docs.show.page",
                "parameters" => ["lang" => "ru", "page" => "readme.html", "product" => "howto", "version" => "1.0"]
            ],
        ]);

        \App\Menu\Entities\Menu::create([
            'parent_id' => $parentMenu->id,
            'filter' => ["lang" => "ru", "product" => "howto", "version" => "1.0"],
            'part' => 'readme.html',
            'title' => 'Readme',
            'href' => 'readme.html',
            'route' => [
                "name" => "docs.show.page",
                "parameters" => ["lang" => "ru", "page" => "readme.html", "product" => "howto", "version" => "1.0"]
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    }
};
