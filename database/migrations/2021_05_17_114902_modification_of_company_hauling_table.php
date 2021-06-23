<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModificationOfCompanyHaulingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_hauling', function (Blueprint $table) {
            $table->dropColumn('date');
            $table->dropColumn('name');
            $table->dropColumn('driver');
            $table->dropColumn('status');
        });
        Schema::table('company_hauling', function (Blueprint $table) {
            $table->bigInteger('company_id')->nullable()->after('id');
            $table->string('driver_name',255)->nullable()->after('branch_id');
            $table->date('date')->nullable()->after('driver_name');
            $table->longText('description')->nullable()->collation('utf8_general_ci')->after('date');            
            $table->unsignedTinyInteger('status')->default(0)->comment('0: Not Confirmed, 1: Confirmed, 2: Completed')->after('description'); 
        });
        Schema::rename('company_hauling','company_haulings');
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
