<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_packages', function (Blueprint $table) {
            $table->id();
            //
            $table->unsignedBigInteger('package_id')->default(0)->comment('package id');
            $table->unsignedBigInteger('company_id')->default(0)->comment('company id');
            $table->unsignedBigInteger('branch_id')->default(0)->comment('branch id');
            $table->string('cust_haul', 255)->nullable()->collation('utf8_general_ci');
            $table->string('cust_box', 255)->nullable()->collation('utf8_general_ci');
            $table->string('cust_price', 255)->nullable()->collation('utf8_general_ci');
            //
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
        Schema::dropIfExists('company_packages');
    }
}

/*
id .
packageId .
companyId .
branchId .
custHaul .
custBox . 
custPrice . 
*/
