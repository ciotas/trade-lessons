<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSortToVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->integer('sort')->nullable()->default(1)->after('cover_url');
            $table->index(['lesson_id', 'sort']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropColumn('sort');
        });
    }
}
