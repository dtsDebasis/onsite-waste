<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyIdFieldToBranchSpecialties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('branch_specialties', function (Blueprint $table) {
            $table->unsignedBigInteger('company_id')->default(0)->comment('Company id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('branch_specialties', function (Blueprint $table) {
            $table->dropColumn('branch_specialties');
        });
    }
}
