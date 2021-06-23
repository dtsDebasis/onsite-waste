<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWasteTypeKnowledgeContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kw_content', function (Blueprint $table) {
            $table->enum('waste_type', ['te', 'pickup', 'hybrid'])->comment('te=TE Only, pickup=Pickup Only, hybrid=Both TE and Pickup')->collation('utf8_general_ci');
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
