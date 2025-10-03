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
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('aspect_id')->nullable();
            $table->bigInteger('parent_id')->nullable();
            $table->string('lang', 100);
            $table->string('product');
            $table->string('version', 100);
            $table->string('part');
            $table->string('page');
            $table->text('title');
            $table->longText('content');
            $table->timestamps();

            //$table->unique(['lang', 'product', 'version', 'page']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
