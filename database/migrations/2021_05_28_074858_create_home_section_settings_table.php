<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHomeSectionSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('home_section_settings', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('section_id')->unsigned();
            $table->tinyInteger('customer_type')->collation('utf8_general_ci')->comment('0 = All, otherwise=>specific')->default(0);
            $table->text('locations')->nullable()->collation('utf8_general_ci');
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
        Schema::dropIfExists('home_section_settings');
    }
}
