<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchCyclingInformations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branch_cycling_informations', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('branch_id')->unsigned();
            $table->longText('last_run_information')->nullable()->collation('utf8_general_ci');  
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
        Schema::dropIfExists('branch_cycling_informations');
    }
}
