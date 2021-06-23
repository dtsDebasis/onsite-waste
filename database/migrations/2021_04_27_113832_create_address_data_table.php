<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address_data', function (Blueprint $table) {
            $table->id();
            //
            $table->string('addressline1', 255)->nullable()->collation('utf8_general_ci');
            $table->string('address1', 255)->nullable()->collation('utf8_general_ci');
            $table->string('address2', 255)->nullable()->collation('utf8_general_ci');
            $table->string('locality', 255)->nullable()->collation('utf8_general_ci');
            $table->string('state', 255)->nullable()->collation('utf8_general_ci');
            $table->string('postcode', 255)->nullable()->collation('utf8_general_ci');
            $table->string('country', 255)->nullable()->collation('utf8_general_ci');
            $table->string('latitude', 255)->nullable()->collation('utf8_general_ci');
            $table->string('longitude', 255)->nullable()->collation('utf8_general_ci');

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
        Schema::dropIfExists('address_data');
    }
}
/*
"address1" => ""
"address2" => "ED-85 , Tagore Garden, Block ED, Tagore Garden, Tagore Garden Extension"
"locality" => "New Delhi"
"state" => "DL"
"postcode" => "110027"
"country" => "India"
"latitude" => "28.6488831"
"longitude" => "77.11481"

*/