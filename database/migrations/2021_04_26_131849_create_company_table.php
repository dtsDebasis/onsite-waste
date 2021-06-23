<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company', function (Blueprint $table) {
            $table->id();
            /// 
            $table->string('company_name', 255)->nullable()->collation('utf8_general_ci');
            $table->string('company_number', 255)->nullable()->collation('utf8_general_ci')->unique();
            $table->string('phone', 255)->nullable()->collation('utf8_general_ci');
            $table->string('email', 255)->nullable()->collation('utf8_general_ci')->unique();
            $table->string('website', 255)->nullable()->collation('utf8_general_ci')->unique();
            $table->unsignedBigInteger('specality_id')->default(0);
            $table->text('lead_source')->collation('utf8_general_ci'); 
            $table->text('leadsource_2')->collation('utf8_general_ci'); 
            $table->unsignedBigInteger('addressdata_id')->default(0)->comment('AddressData id');
            $table->boolean('is_active')->default(1);	
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
        Schema::dropIfExists('company');
        // Schema::table('company', function (Blueprint $table) {
        //     $table->dropColumn('company');
        // });
    }
}


/*
id .
companyName .
companyNumber .
phone .
email . 
website .
specalityId .
leadSource1
leadSource2
isActive .

*/