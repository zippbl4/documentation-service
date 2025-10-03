<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('aspects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('path_id')->index();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedTinyInteger('status')->default(1);
            $table->unsignedBigInteger('sort_order');

            $table->string('name');
            $table->string('lang');
            $table->string('product');
            $table->string('version');
            $table->timestamps();
        });

        Schema::create('paths', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('status')->default(1);

            $table->string('name');
            $table->string('driver');
            $table->string('root');
            $table->string('pattern');
            $table->text('nginx_conf_template');
            $table->timestamps();
        });

        Schema::create('decorators', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aspect_id')->index();
            $table->unsignedTinyInteger('status')->default(1);
            $table->unsignedBigInteger('sort_order');

            $table->string('name');
            $table->longText('user_custom_template')->nullable();
            $table->timestamps();
        });

        Schema::create('mappers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aspect_id')->index();
            $table->unsignedTinyInteger('status')->default(1);

            $table->string('name');
            $table->string('pattern');
            $table->string('map_from');
            $table->string('map_to');
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aspect_id')->index();
            $table->string('lang');
            $table->string('version');
            $table->string('product');
            $table->string('archive_hash');
            $table->string('job_uuid');
            $table->string('root_folder');
            $table->string('root_path');
            $table->timestamps();
        });

        Schema::create('validations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aspect_id')->index();
            $table->unsignedTinyInteger('status')->default(1);
            $table->string('page');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aspects');
        Schema::dropIfExists('paths');
        Schema::dropIfExists('decorators');
        Schema::dropIfExists('validations');
        Schema::dropIfExists('mappers');
        Schema::dropIfExists('products');
    }
};
