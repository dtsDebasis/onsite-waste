<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhoneToCompanyBranch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_branch', function (Blueprint $table) {
            $table->dropColumn('specality_id');
            $table->dropColumn('rec_account_id');
            $table->dropColumn('state_id');
            $table->dropColumn('city_id');
            $table->dropColumn('zipcode');
        });
        Schema::table('company_branch', function (Blueprint $table) {
            $table->bigInteger('company_number')->nullable()->after('company_id')->collation('utf8_general_ci');
            $table->bigInteger('phone')->nullable()->after('name')->collation('utf8_general_ci');
            $table->string('owner',255)->nullable()->after('phone')->collation('utf8_general_ci');
            $table->string('lead_source',255)->nullable()->after('phone')->collation('utf8_general_ci');
            $table->string('leadsource_2',255)->nullable()->after('phone')->collation('utf8_general_ci');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_branch', function (Blueprint $table) {
            //
        });
    }
}
