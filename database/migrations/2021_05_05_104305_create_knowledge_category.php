<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKnowledgeCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kw_categories', function (Blueprint $table) {
            $table->id();
			$table->unsignedBigInteger('kw_category_id')->nullable()->default(0);
			$table->unsignedTinyInteger('status')->nullable()->default(1);
			$table->string('title', 255)->nullable();
            $table->string('short_desc', 255)->nullable();
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
			$table->softDeletes('deleted_at', 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kw_categories', function (Blueprint $table) {
            //
        });
    }
}
