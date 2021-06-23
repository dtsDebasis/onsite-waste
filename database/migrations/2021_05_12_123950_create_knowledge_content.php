<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKnowledgeContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kw_content', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('category_id')->unsigned();
            $table->bigInteger('sub_category_id')->unsigned();
            $table->string('title', 255)->nullable()->collation('utf8_general_ci');
            $table->string('short_desc', 255)->nullable()->collation('utf8_general_ci');
            $table->string('type',100)->nullable()->collation('utf8_general_ci');
            $table->longText('details')->nullable()->collation('utf8_general_ci');
            $table->unsignedTinyInteger('status')->default(1);
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
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
        Schema::table('kw_content', function (Blueprint $table) {
            //
        });
    }
}
