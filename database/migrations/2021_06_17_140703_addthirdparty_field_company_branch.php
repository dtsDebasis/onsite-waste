<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddthirdpartyFieldCompanyBranch extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_branch', function (Blueprint $table) {
            $table->string('hubspot_id',250)->nullable()->collation('utf8_general_ci');
            $table->string('zendesk_id',250)->nullable()->collation('utf8_general_ci');
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
