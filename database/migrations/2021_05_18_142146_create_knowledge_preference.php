<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKnowledgePreference extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kw_user_preference', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('knowledge_wizard_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();                        
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kw_user_preference', function (Blueprint $table) {
            //
        });
    }
}
