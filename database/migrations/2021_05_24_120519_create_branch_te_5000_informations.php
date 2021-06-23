<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchTe5000Informations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_te_5000_informations', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('branch_id')->unsigned();
            $table->bigInteger('package_id')->unsigned();
            $table->longText('te_5000_info')->nullable()->collation('utf8_general_ci'); 
            $table->string('te_5000_imei',200)->nullable()->collation('utf8_general_ci');  
            $table->unsignedTinyInteger('status')->default(0);
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
        Schema::dropIfExists('branch_te_5000_informations');
    }
}
