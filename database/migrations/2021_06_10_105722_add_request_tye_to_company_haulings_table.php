<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRequestTyeToCompanyHaulingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('company_haulings', function (Blueprint $table) {
        //     $table->dropColumn('number_of_boxes');
        // });
        Schema::table('company_haulings', function (Blueprint $table) {
            $table->tinyInteger('request_type')->collation('utf8_general_ci')->comment('1 => ASAP, 2=> ASAP - No Change to Frequency')->default(1)->after('driver_name');
            $table->tinyInteger('supplies_requested')->collation('utf8_general_ci')->comment('0 => No, 1=> Yes')->default(0)->after('driver_name');
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
            //
        });
    }
}
