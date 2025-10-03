<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCorrectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('corrections', function (Blueprint $table) {
            $table->id();
            $table->string('release_name');
            $table->unsignedInteger('user_crm_id');
            $table->string('user_name');
            $table->unsignedInteger('is_approved');
            $table->unsignedInteger('is_merged');
            $table->unsignedInteger('is_archived');
            $table->string('page_url');
            $table->string('page_xpath');
            $table->text('html_eng');
            $table->text('html_rus_old');
            $table->text('html_rus_new');
            $table->timestamps();
            $table->timestamp('edited_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('corrections');
    }
}
