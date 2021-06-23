<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKnowledgeWizard extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kw_wizard', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('category_id')->unsigned();
            $table->bigInteger('sub_category_id')->unsigned();
            $table->string('title', 255)->nullable()->collation('utf8_general_ci');
            $table->string('short_desc', 255)->nullable()->collation('utf8_general_ci');
            $table->text('benifits')->nullable()->collation('utf8_general_ci');
            $table->longText('details')->nullable()->collation('utf8_general_ci');
            $table->unsignedTinyInteger('status')->default(1);
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
        Schema::table('kw_wizard', function (Blueprint $table) {
            //
        });
    }
}
