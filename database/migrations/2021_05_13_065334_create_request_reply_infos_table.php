<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestReplyInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_reply_infos', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('request_id')->unsigned();
            $table->bigInteger('from_user_id')->unsigned()->nullable();
            $table->bigInteger('to_user_id')->unsigned()->nullable();
            $table->longText('description')->nullable()->collation('utf8_general_ci');                 
            $table->timestamps();
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
        Schema::dropIfExists('request_reply_infos');
    }
}
