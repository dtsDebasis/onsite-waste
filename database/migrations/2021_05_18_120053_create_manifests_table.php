<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateManifestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('manifests', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('hauling_id')->unsigned();
            $table->string('person_name',255)->nullable()->collation('utf8_general_ci');  
            $table->date('date')->nullable();  
            $table->bigInteger('number_of_container')->default(0)->nullable(); 
            $table->string('branch_address',255)->nullable()->collation('utf8_general_ci'); 
            $table->unsignedTinyInteger('status')->comment('0: Not Confirmed, 1: Completed')->default(1);                       
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
        Schema::dropIfExists('manifests');
    }
}
