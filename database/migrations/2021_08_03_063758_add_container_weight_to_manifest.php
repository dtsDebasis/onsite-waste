<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContainerWeightToManifest extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('manifests', function (Blueprint $table) {
            $table->bigInteger('items_weight')->default(0)->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('manifests', function (Blueprint $table) {
            //
        });
    }
}
