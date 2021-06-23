<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUniqueIndexToCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company', function (Blueprint $table) {
            $table->dropColumn('company_number');
            $table->dropColumn('email');
            $table->dropColumn('website');
        });
        Schema::table('company', function (Blueprint $table) {
            $table->string('company_number', 255)->nullable()->collation('utf8_general_ci')->after('company_name');
            $table->string('email', 255)->nullable()->collation('utf8_general_ci')->after('company_number');
            $table->string('website', 255)->nullable()->collation('utf8_general_ci')->after('phone');
    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company', function (Blueprint $table) {
            //
        });
    }
}
