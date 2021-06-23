<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanySpecialityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_speciality', function (Blueprint $table) {
            $table->id();
            //
            $table->unsignedBigInteger('company_id')->default(0)->comment('Company id');
            $table->unsignedBigInteger('specality_id')->default(0)->comment('Specality id');
            //
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
        Schema::dropIfExists('company_speciality');
    }
}
/*
company_id
specility_id
*/