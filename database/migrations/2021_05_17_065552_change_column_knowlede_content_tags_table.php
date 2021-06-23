<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnKnowledeContentTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('knowledge_content_tags', function (Blueprint $table) {
            $table->dropColumn('knowledge_center_id');
        });
        Schema::table('knowledge_content_tags', function (Blueprint $table) {
            $table->bigInteger('knowledge_content_id')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('knowledge_content_tags', function (Blueprint $table) {
            //
        });
    }
}
