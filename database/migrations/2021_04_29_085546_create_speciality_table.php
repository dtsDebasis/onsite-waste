<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSpecialityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('speciality', function (Blueprint $table) {
            $table->id();
            //
            $table->string('name', 255)->nullable()->collation('utf8_general_ci');  
            $table->enum('status', ['y', 'n'])->collation('utf8_general_ci');
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
        Schema::dropIfExists('speciality');
    }
}
