<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestCompanyInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_company_infos', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->string('company_name', 255)->nullable()->collation('utf8_general_ci');
            $table->string('company_email', 255)->nullable()->collation('utf8_general_ci');
            $table->bigInteger('company_phone')->nullable()->collation('utf8_general_ci');
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
        Schema::dropIfExists('guest_company_infos');
    }
}
