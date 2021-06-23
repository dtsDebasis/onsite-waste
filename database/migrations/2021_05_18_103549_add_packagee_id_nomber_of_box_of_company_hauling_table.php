<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPackageeIdNomberOfBoxOfCompanyHaulingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_haulings', function (Blueprint $table) {            
            $table->dropColumn('status');
        });
        Schema::table('company_haulings', function (Blueprint $table) {
            $table->bigInteger('package_id')->nullable()->after('branch_id');
            $table->bigInteger('number_of_boxes')->nullable()->after('package_id')->default(0);
            $table->unsignedTinyInteger('status')->default(0)->comment('0: Not Confirmed, 1: Confirmed, 2: Pickup Done, 3: Completed, 4: Declined')->after('description'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_hauling', function (Blueprint $table) {
            //
        });
    }
}
