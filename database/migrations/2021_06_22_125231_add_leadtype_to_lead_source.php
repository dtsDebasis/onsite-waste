<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLeadtypeToLeadSource extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lead_sources', function (Blueprint $table) {
            $table->tinyInteger('lead_type')->collation('utf8_general_ci')->comment('1 => Source One, 2=> Source Two')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lead_sources', function (Blueprint $table) {
            //
        });
    }
}
