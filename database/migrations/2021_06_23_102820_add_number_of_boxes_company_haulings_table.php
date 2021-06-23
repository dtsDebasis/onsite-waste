<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumberOfBoxesCompanyHaulingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_haulings', function (Blueprint $table) {
            $table->bigInteger('number_of_boxes')->nullable()->after('package_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_haulings', function (Blueprint $table) {
            $table->dropColumn('number_of_boxes');
        });
    }
}
