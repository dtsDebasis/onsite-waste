<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeStatusToSpecialityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('speciality', function (Blueprint $table) {
            $table->dropColumn('status');
        });
        Schema::table('speciality', function (Blueprint $table) {            
            $table->unsignedTinyInteger('status')->default(0)->comment('1 = Yes, 0 = No')->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('speciality', function (Blueprint $table) {
            
        });
    }
}
