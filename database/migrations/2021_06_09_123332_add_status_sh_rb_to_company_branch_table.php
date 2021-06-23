<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusShRbToCompanyBranchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_branch', function (Blueprint $table) {
            $table->bigInteger('sh_rop')->collation('utf8_general_ci')->nullable()->after('lead_source');
            $table->string('sh_container_type',250)->collation('utf8_general_ci')->nullable()->after('sh_rop');;
            $table->bigInteger('rb_rop')->collation('utf8_general_ci')->nullable()->after('sh_container_type');;
            $table->string('rb_container_type',250)->collation('utf8_general_ci')->nullable()->after('rb_rop');;
            $table->tinyInteger('status')->collation('utf8_general_ci')->comment('0 => inactive, 1=> Active')->default(1)->after('rb_container_type');;
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
