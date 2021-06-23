<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestRequestInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_request_infos', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->string('company_name', 255)->nullable()->collation('utf8_general_ci');
            $table->string('company_email', 255)->nullable()->collation('utf8_general_ci');
            $table->bigInteger('company_phone')->nullable()->collation('utf8_general_ci');
            $table->unsignedTinyInteger('request_type')->nullable()->default(0)->comment('1:Emission calculator,2:Cost Analysis');
            $table->unsignedTinyInteger('status')->default(1);            
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
        Schema::dropIfExists('guest_request_infos');
    }
}
