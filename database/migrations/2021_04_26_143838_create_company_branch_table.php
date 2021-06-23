<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyBranchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_branch', function (Blueprint $table) {
            $table->id();
            ///
            $table->unsignedBigInteger('company_id')->default(0)->comment('Company id');
            $table->unsignedBigInteger('specality_id')->default(0)->comment('Specality id');
            // addressdata_id
            $table->unsignedBigInteger('addressdata_id')->default(0)->comment('Specality id');
            $table->unsignedBigInteger('rec_account_id')->default(0);

            $table->string('name', 255)->nullable()->collation('utf8_general_ci');
            $table->unsignedBigInteger('state_id')->default(0);
            $table->unsignedBigInteger('city_id')->default(0);
            $table->string('zipcode', 255)->nullable()->collation('utf8_general_ci');
            $table->boolean('is_primary')->default(0);
            ///
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_branch');
    }
}

/*

companyId .
specalityId .
name .
recAccountId .
addrLine1  address
addrLine2  address
stateId .
cityId .
zipcode .
isPrimary .

*/